<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public static function create(): self
    {
        return new static('File not found');
    }
}
