<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

use Throwable;

final class InvalidViewTemplateException extends TelegramGitNotifierException
{
    public static function create(
        string $view,
        Throwable $previous = null
    ): self {
        return new self(
            "Invalid view template: {$view}",
            0,
            $previous
        );
    }
}
