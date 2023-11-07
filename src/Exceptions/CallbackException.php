<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

use Exception;

final class CallbackException extends TelegramGitNotifierException
{
    public static function answer(Exception $exception): self
    {
        return new self('Error answering callback query: ' . $exception->getMessage());
    }
}
