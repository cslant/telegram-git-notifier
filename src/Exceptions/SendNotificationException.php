<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

final class SendNotificationException extends TelegramGitNotifierException
{
    public static function create(): self
    {
        return new self('Can\'t send notification');
    }
}
