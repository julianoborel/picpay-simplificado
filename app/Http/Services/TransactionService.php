<?php

namespace App\Http\Services;

use App\Http\Exceptions\TransactionException;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function createTransaction(array $validatedData): Transaction
    {
        $this->validateBusinessRules($validatedData);
        $this->authorizeTransaction();
        $this->transferBalances($validatedData);

        $transaction = $this->createTransactionRecord($validatedData);
        $this->sendNotification($transaction);

        return $transaction;
    }

    private function validateBusinessRules(array $data): void
    {
        $payer = User::findOrFail($data['payer']);
        $payee = User::findOrFail($data['payee']);

        if ($payer->type === 'merchant') {
            throw TransactionException::merchantCannotSendMoney();
        }

        if ($payer->balance < $data['value']) {
            throw TransactionException::insufficientBalance();
        }
    }

    private function authorizeTransaction(): void
    {
        $response = Http::get('https://util.devi.tools/api/v2/authorize');

        if (!$response->ok() || $response->json('data.authorization') !== true) {
            throw TransactionException::unauthorizedTransaction();
        }
    }

    private function transferBalances(array $data): void
    {
        DB::transaction(function () use ($data) {
            $payer = User::findOrFail($data['payer']);
            $payee = User::findOrFail($data['payee']);

            $payer->balance -= $data['value'];
            $payee->balance += $data['value'];

            $payer->save();
            $payee->save();
        });
    }

    private function createTransactionRecord(array $data): Transaction
    {
        return Transaction::create([
            'payer_id' => $data['payer'],
            'payee_id' => $data['payee'],
            'value' => $data['value'],
            'status' => 'completed'
        ]);
    }

    private function sendNotification(Transaction $transaction): void
    {
        try {
            $payee = $transaction->payee;
            $payer = $transaction->payer;

            $response = Http::timeout(5)
                ->post('https://util.devi.tools/api/v1/notify', [
                    'email' => $payee->email,
                    'message' => "Você recebeu R$ {$transaction->value} de {$payer->name}"
                ]);

            $transaction->update([
                'notification_status' => $response->status() === 204 ? 'sent' : 'failed',
                'notified_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error('Falha na notificação: ' . $e->getMessage());
            $transaction->update(['notification_status' => 'failed']);
        }
    }
}
