<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;

class SendNotificationException extends Exception
{
    public static function create(): self
    {
        return new self('Can\'t send notification');
    }
}
