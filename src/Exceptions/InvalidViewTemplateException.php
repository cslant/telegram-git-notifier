<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;

class InvalidViewTemplateException extends Exception
{
    public static function create(string $view): static
    {
        return new static("Invalid view template: {$view}");
    }
}
