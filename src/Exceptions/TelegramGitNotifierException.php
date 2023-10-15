<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;

class TelegramGitNotifierException extends Exception
{
    public static function isEmpty(): self
    {
        return new static('Telegram Git Notifier is empty');
    }

    public static function invalid(): self
    {
        return new static('Telegram Git Notifier is invalid');
    }
}
