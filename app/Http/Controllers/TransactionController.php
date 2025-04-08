<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function store(TransactionRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $transaction = $this->transactionService->createTransaction(
                $request->validated()
            );

            DB::commit();

            return response()->json([
                'message' => 'Transação realizada com sucesso',
                'data' => new TransactionResource($transaction),
                'notification_status' => $transaction->notification_status
            ], JsonResponse::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage(),
                'error_code' => $e->getCode() >= 400 ? $e->getCode() : 500
            ], $e->getCode() >= 400 && $e->getCode() < 600
                ? $e->getCode()
                : JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
