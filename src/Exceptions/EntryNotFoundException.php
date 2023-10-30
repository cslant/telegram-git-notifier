<?php

namespace LbilTech\TelegramGitNotifier\Exceptions;

final class EntryNotFoundException extends TelegramGitNotifierException
{
    public static function fileNotFound(): self
    {
        return new self('File not found');
    }

    public static function configNotFound(string $config): self
    {
        return new self("Config {$config} not found");
    }

    public static function viewNotFound(string $view): self
    {
        return new self("View {$view} not found");
    }
}
