<?php

namespace LbilTech\TelegramGitNotifier;

use LbilTech\TelegramGitNotifier\Interfaces\Structures\NotificationInterface;
use LbilTech\TelegramGitNotifier\Structures\App;
use LbilTech\TelegramGitNotifier\Structures\Event;
use LbilTech\TelegramGitNotifier\Structures\Notification;
use LbilTech\TelegramGitNotifier\Trait\ActionEventTrait;
use Telegram;

class Notifier implements NotificationInterface
{
    use App;
    use Event;
    use Notification;
    use ActionEventTrait;

    public function __construct(
        Telegram $telegram = null
    ) {
        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId();
    }
}
