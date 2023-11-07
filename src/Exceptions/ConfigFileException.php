<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

final class ConfigFileException extends TelegramGitNotifierException
{
    public static function settingFile(?string $settingFile = null): self
    {
        return new self('Something went wrong while reading settings file. Check your settings file path: ' . ($settingFile ?? 'null'));
    }

    public static function platformFile(?string $platform = null, ?string $platformFile = null): self
    {
        return new self('Something went wrong while reading platform file. Check your platform file path: ' . ($platformFile ?? 'null') . ' for platform: ' . ($platform ?? 'null'));
    }
}
