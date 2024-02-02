<?php

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

class Notifier implements NotificationInterface, EventInterface
{
    use App;
    use Notification;

    use EventTrait;

    public Event $event;

    public Client $client;

    /**
     * @param Telegram|null $telegram
     * @param string|null $chatBotId
     * @param Event|null $event
     * @param string|null $platform
     * @param string|null $platformFile
     * @param Client|null $client
     *
     * @throws ConfigFileException
     */
    public function __construct(
        Telegram $telegram = null,
        ?string $chatBotId = null,
        Event $event = null,
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
        Client $client = null,
    ) {
        $this->event = $event ?? new Event();
        $this->setPlatFormForEvent($platform, $platformFile);
        $this->validatePlatformFile();

        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId($chatBotId);

        $this->client = $client ?? new Client();
    }

    public function parseNotifyChatIds(?string $chatIds = null): array
    {
        $chatData = explode(
            NotificationConstant::CHAT_ID_PAIRS_SEPARATOR,
            $chatIds ?? config('telegram-git-notifier.bot.notify_chat_ids')
        );
        $chatThreadMapping = [];

        foreach ($chatData as $data) {
            [$chatId, $threadIds] = explode(NotificationConstant::CHAT_THREAD_ID_SEPARATOR, $data) + [null, null];
            $chatThreadMapping[$chatId] = $threadIds
                ? explode(NotificationConstant::THREAD_ID_SEPARATOR, $threadIds)
                : [];
        }

        return $chatThreadMapping;
    }
}
