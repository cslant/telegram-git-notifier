<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

final class MessageIsEmptyException extends TelegramGitNotifierException
{
    public static function create(): self
    {
        return new self('Message is empty');
    }
}
