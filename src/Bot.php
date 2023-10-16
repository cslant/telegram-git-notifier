<?php

namespace LbilTech\TelegramGitNotifier;

use LbilTech\TelegramGitNotifier\Interfaces\BotInterface;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\AppInterface;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\EventInterface;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\SettingInterface;
use LbilTech\TelegramGitNotifier\Structures\App;
use LbilTech\TelegramGitNotifier\Structures\Event;
use LbilTech\TelegramGitNotifier\Structures\Setting;
use LbilTech\TelegramGitNotifier\Trait\BotSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\EventSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\ValidationEventTrait;
use LbilTech\TelegramGitNotifier\Trait\ActionEventTrait;
use Telegram;

class Bot implements AppInterface, EventInterface, SettingInterface, BotInterface
{
    use App;
    use Event;
    use Setting;
    use ValidationEventTrait;
    use ActionEventTrait;
    use BotSettingTrait;
    use EventSettingTrait;

    public function __construct(Telegram $telegram = null)
    {
        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId();
    }

    public function setMyCommands(
        array $menuCommand,
        ?string $view = null
    ): void {
        $this->telegram->setMyCommands([
            'commands' => json_encode($menuCommand)
        ]);
        $this->sendMessage(
            view(
                $view ??
                config('telegram-git-notifier.view.tools.set_menu_cmd')
            )
        );
    }

    public function isCallback(): bool
    {
        if ($this->telegram->getUpdateType() === Telegram::CALLBACK_QUERY) {
            return true;
        }

        return false;
    }

    public function isMessage(): bool
    {
        if ($this->telegram->getUpdateType() === Telegram::MESSAGE) {
            return true;
        }

        return false;
    }

    public function isOwner(): bool
    {
        if ($this->telegram->ChatID() == $this->chatBotId) {
            return true;
        }

        return false;
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
