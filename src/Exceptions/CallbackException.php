<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;

class CallbackException extends Exception
{
    public static function isEmpty(): self
    {
        return new static('Callback is empty');
    }

    public static function invalid(): self
    {
        return new static('Callback is invalid');
    }
}
