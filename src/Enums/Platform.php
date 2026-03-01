<?php

namespace CSlant\TelegramGitNotifier\Enums;

enum Platform: string
{
    case GitHub = 'github';
    case GitLab = 'gitlab';

    public function eventSeparator(): string
    {
        return match ($this) {
            self::GitHub => 'gh.',
            self::GitLab => 'gl.',
        };
    }

    public function webhookEventHeader(): string
    {
        return match ($this) {
            self::GitHub => 'HTTP_X_GITHUB_EVENT',
            self::GitLab => 'HTTP_X_GITLAB_EVENT',
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
        return self::GitHub;
    }
}
