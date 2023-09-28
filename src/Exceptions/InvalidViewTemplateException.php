<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;
use Throwable;

class InvalidViewTemplateException extends Exception
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
