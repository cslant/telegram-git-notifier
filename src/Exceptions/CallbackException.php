<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

final class CallbackException extends TelegramGitNotifierException
{
    public static function isEmpty(): self
    {
        return new self('Callback is empty');
    }

    public static function invalid(): self
    {
        return new self('Callback is invalid');
    }
}
