<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;

class MessageIsEmptyException extends Exception
{
    public static function create(): self
    {
        return new self('Message is empty');
    }
}
