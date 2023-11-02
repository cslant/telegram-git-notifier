<?php

namespace CSlant\TelegramGitNotifier\Structures;

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
        return $this->telegram->ChatID() == $this->chatBotId;
    }

    public function isNotifyChat(): bool
    {
        $chatIds = config('telegram-git-notifier.bot.notify_chat_ids');
        if (in_array($this->telegram->ChatID(), $chatIds)) {
            return true;
        }

        return false;
    }
}
