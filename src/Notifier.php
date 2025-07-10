<?php

declare(strict_types=1);

namespace CSlant\TelegramGitNotifier;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Constants\NotificationConstant;
use CSlant\TelegramGitNotifier\Exceptions\ConfigFileException;
use CSlant\TelegramGitNotifier\Interfaces\EventInterface;
use CSlant\TelegramGitNotifier\Interfaces\Structures\NotificationInterface;
use CSlant\TelegramGitNotifier\Models\Event;
use CSlant\TelegramGitNotifier\Structures\App;
use CSlant\TelegramGitNotifier\Structures\Notification;
use CSlant\TelegramGitNotifier\Trait\EventTrait;
use GuzzleHttp\Client;
use Telegram;

/**
 * Class Notifier
 * 
 * Handles the core notification functionality for Telegram Git events.
 * Implements both NotificationInterface and EventInterface for handling
 * notifications and events respectively.
 */
class Notifier implements NotificationInterface, EventInterface
{
    use App;
    use Notification;
    use EventTrait;

    /** @var Event The event instance */
    public Event $event;

    /** @var Client The HTTP client for making requests */
    public Client $client;

    /**
     * Initialize the Notifier with required dependencies
     *
     * @param Telegram|null $telegram The Telegram bot instance
     * @param string|null $chatBotId The chat bot ID
     * @param Event|null $event The event instance
     * @param string|null $platform The platform (e.g., 'github', 'gitlab')
     * @param string|null $platformFile Path to the platform configuration file
     * @param Client|null $client The HTTP client
     *
     * @throws ConfigFileException If platform configuration is invalid
     */
    public function __construct(
        ?Telegram $telegram = null,
        ?string $chatBotId = null,
        ?Event $event = null,
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
        ?Client $client = null
    ) {
        $this->initializeEvent($event, $platform, $platformFile);
        $this->initializeTelegram($telegram, $chatBotId);
        $this->client = $client ?? new Client();
    }

    /**
     * Parse notification chat IDs from configuration
     *
     * Expected format: "chatId1:thread1,thread2|chatId2:thread3,thread4"
     *
     * @param string|null $chatIds The chat IDs string to parse, falls back to config if null
     * @return array<string, array<int, string>> Mapped chat IDs to their thread IDs
     */
    public function parseNotifyChatIds(?string $chatIds = null): array
    {
        $chatData = $this->getChatData($chatIds);
        return $this->mapChatThreads($chatData);
    }

    /**
     * Initialize the event handler
     */
    private function initializeEvent(?Event $event, string $platform, ?string $platformFile): void
    {
        $this->event = $event ?? new Event();
        $this->setPlatFormForEvent($platform, $platformFile);
        $this->validatePlatformFile();
    }

    /**
     * Initialize the Telegram client
     */
    private function initializeTelegram(?Telegram $telegram, ?string $chatBotId): void
    {
        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId($chatBotId);
    }

    /**
     * Get chat data from config or provided string
     */
    private function getChatData(?string $chatIds): array
    {
        $chatConfig = $chatIds ?? config('telegram-git-notifier.bot.notify_chat_ids');
        return explode(NotificationConstant::CHAT_ID_PAIRS_SEPARATOR, (string) $chatConfig);
    }

    /**
     * Map chat IDs to their respective thread IDs
     *
     * @param array<string> $chatData Array of chat ID and thread ID pairs
     * @return array<string, array<int, string>> Mapped chat threads
     */
    private function mapChatThreads(array $chatData): array
    {
        $chatThreadMapping = [];
        
        foreach ($chatData as $data) {
            if (empty(trim($data))) {
                continue;
            }
            
            [$chatId, $threadIds] = array_merge(
                explode(NotificationConstant::CHAT_THREAD_ID_SEPARATOR, $data, 2),
                [null, null]
            );
            
            $chatThreadMapping[$chatId] = $threadIds 
                ? explode(NotificationConstant::THREAD_ID_SEPARATOR, $threadIds)
                : [];
        }
        
        return $chatThreadMapping;
    }
}
