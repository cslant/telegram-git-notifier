<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

use Exception;

final class BotException extends TelegramGitNotifierException
{
    public static function editMessageText(Exception $exception): self
    {
        return new self('Error editing message text: ' . $exception->getMessage());
    }

    public static function editMessageReplyMarkup(Exception $exception): self
    {
        return new self('Error sending message: ' . $exception->getMessage());
    }
}
