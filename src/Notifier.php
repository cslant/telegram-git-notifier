<?php

namespace CSlant\TelegramGitNotifier;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Constants\NotificationConstant;
use CSlant\TelegramGitNotifier\DTOs\ChatTarget;
use CSlant\TelegramGitNotifier\Exceptions\ConfigFileException;
use CSlant\TelegramGitNotifier\Interfaces\EventInterface;
use CSlant\TelegramGitNotifier\Interfaces\Structures\NotificationInterface;
use CSlant\TelegramGitNotifier\Models\Event;
use CSlant\TelegramGitNotifier\Structures\App;
use CSlant\TelegramGitNotifier\Structures\Notification;
use CSlant\TelegramGitNotifier\Trait\EventTrait;
use GuzzleHttp\Client;
use Telegram;

class Notifier implements NotificationInterface, EventInterface
{
    use App;
    use Notification;

    use EventTrait;

    public Event $event;

    public Client $client;

    /**
     * @throws ConfigFileException
     */
    public function __construct(
        ?Telegram $telegram = null,
        ?string $chatBotId = null,
        ?Event $event = null,
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
        ?Client $client = null,
    ) {
        $this->event = $event ?? new Event();
        $this->setPlatFormForEvent($platform, $platformFile);
        $this->validatePlatformFile();

        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId($chatBotId);

        $this->client = $client ?? new Client();
    }

    /**
     * Parse notification chat IDs string into structured array.
     *
     * @return array<string, list<string>> Map of chatId => threadIds
     */
    public function parseNotifyChatIds(?string $chatIds = null): array
    {
        $raw = $chatIds ?? config('telegram-git-notifier.bot.notify_chat_ids');

        if (empty($raw)) {
            return [];
        }

        $targets = ChatTarget::parseMultiple($raw);
        $mapping = [];

        foreach ($targets as $target) {
            if ($target->chatId !== '') {
                $mapping[$target->chatId] = $target->threadIds;
            }
        }

        return $mapping;
    }

    /**
     * Parse notification chat IDs into ChatTarget DTOs.
     *
     * @return list<ChatTarget>
     */
    public function getChatTargets(?string $chatIds = null): array
    {
        $raw = $chatIds ?? config('telegram-git-notifier.bot.notify_chat_ids');

        return ChatTarget::parseMultiple($raw ?? '');
    }
}
