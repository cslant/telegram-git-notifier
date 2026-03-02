<?php

namespace CSlant\TelegramGitNotifier\Enums;

enum Platform: string
{
    case GITHUB = 'github';
    case GITLAB = 'gitlab';

    public function eventSeparator(): string
    {
        return match ($this) {
            self::GITHUB => 'gh.',
            self::GITLAB => 'gl.',
        };
    }

    public function webhookEventHeader(): string
    {
        return match ($this) {
            self::GITHUB => 'HTTP_X_GITHUB_EVENT',
            self::GITLAB => 'HTTP_X_GITLAB_EVENT',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function webhookEventHeaders(): array
    {
        return array_combine(
            array_map(fn (self $p) => $p->value, self::cases()),
            array_map(fn (self $p) => $p->webhookEventHeader(), self::cases()),
        );
    }

    /**
     * @return array<string, string>
     */
    public static function eventSeparators(): array
    {
        return array_combine(
            array_map(fn (self $p) => $p->value, self::cases()),
            array_map(fn (self $p) => $p->eventSeparator(), self::cases()),
        );
    }

    public static function default(): self
    {
        return self::GITHUB;
    }
}
