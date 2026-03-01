<?php

namespace CSlant\TelegramGitNotifier\DTOs;

readonly class ChatTarget
{
    /**
     * @param string $chatId
     * @param list<string> $threadIds
     */
    public function __construct(
        public string $chatId,
        public array $threadIds = [],
    ) {
    }

    public function hasThreads(): bool
    {
        return $this->threadIds !== [];
    }

    /**
     * Parse a single "chatId:thread1,thread2" string.
     */
    public static function fromString(string $raw): self
    {
        [$chatId, $threadIds] = explode(':', $raw) + [null, null];

        return new self(
            chatId: (string) $chatId,
            threadIds: $threadIds ? explode(',', $threadIds) : [],
        );
    }

    /**
     * Parse "id1;id2:t1,t2;id3" format into an array of ChatTarget.
     *
     * @return list<self>
     */
    public static function parseMultiple(string $raw): array
    {
        if (trim($raw) === '') {
            return [];
        }

        return array_map(
            static fn (string $pair) => self::fromString($pair),
            explode(';', $raw),
        );
    }
}
