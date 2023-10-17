<?php

namespace LbilTech\TelegramGitNotifier;

use GuzzleHttp\Client;
use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\AppInterface;
use LbilTech\TelegramGitNotifier\Interfaces\EventInterface;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\NotificationInterface;
use LbilTech\TelegramGitNotifier\Models\Event;
use LbilTech\TelegramGitNotifier\Structures\App;
use LbilTech\TelegramGitNotifier\Structures\Notification;
use LbilTech\TelegramGitNotifier\Trait\EventTrait;
use Telegram;

class Notifier implements AppInterface, NotificationInterface, EventInterface
{
    use App;
    use Notification;

    use EventTrait;

    public Event $event;

    public Client $client;

    public function __construct(
        Telegram $telegram = null,
        ?string $chatBotId = null,
        Event $event = null,
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
        Client $client = null,
    ) {
        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId($chatBotId);

        $this->event = $event ?? new Event();
        $this->setPlatFormForEvent($platform, $platformFile);

        $this->client = $client ?? new Client();
    }
}
