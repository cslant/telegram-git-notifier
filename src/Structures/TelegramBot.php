<?php

namespace CSlant\TelegramGitNotifier\Structures;

use CSlant\TelegramGitNotifier\Notifier;
use Telegram;

trait TelegramBot
{
    public function isCallback(): bool
    {
        return $this->telegram->getUpdateType() === Telegram::CALLBACK_QUERY;
    }

    public function isMessage(): bool
    {
        return $this->telegram->getUpdateType() === Telegram::MESSAGE;
    }

    public function isOwner(): bool
    {
        return (string) $this->telegram->ChatID() === (string) $this->chatBotId;
    }

    public function isNotifyChat(): bool
    {
        $chatIds = config('telegram-git-notifier.bot.notify_chat_ids');

        $notifyChatIds = Notifier::parseNotifyChatIds($chatIds);

        return in_array((string) $this->telegram->ChatID(), $notifyChatIds, true);
    }
}
