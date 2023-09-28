<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;

class EntryNotFoundException extends Exception
{
    public static function fileNotFound(): self
    {
        return new static('File not found');
    }

    public static function configNotFound($config): self
    {
        return new static("Config {$config} not found");
    }
}
