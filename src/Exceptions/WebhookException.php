<?php

namespace CSlant\TelegramGitNotifier\Exceptions;

final class WebhookException extends TelegramGitNotifierException
{
    public static function set(): self
    {
        return new self('Something went wrong while setting webhook. Check your bot token and app url!');
    }

    public static function delete(): self
    {
        return new self('Something went wrong while deleting webhook. Check your bot token and app url!');
    }

    public static function getUpdates(): self
    {
        return new self('Something went wrong while getting updates. Check your bot token and app url!');
    }

    public static function getWebHookInfo(): self
    {
        return new self('Something went wrong while getting webhook info. Check your bot token and app url!');
    }
}
