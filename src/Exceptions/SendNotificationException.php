<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

final class SendNotificationException extends TelegramGitNotifierException
{
    public static function create(?string $exception = null): self
    {
        return new self('Can\'t send notification. ' . ($exception ?? ''));
    }
}
