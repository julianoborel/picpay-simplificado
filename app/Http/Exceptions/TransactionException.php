<?php

namespace App\Http\Exceptions;

use Exception;

class TransactionException extends Exception
{
    public static function merchantCannotSendMoney(): self
    {
        return new self('Lojistas não podem enviar dinheiro', 422);
    }

    public static function insufficientBalance(): self
    {
        return new self('Saldo insuficiente para a transação', 422);
    }

    public static function unauthorizedTransaction(): self
    {
        return new self('Transação não autorizada', 403);
    }
}
