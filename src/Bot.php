<?php

namespace LbilTech\TelegramGitNotifier;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Interfaces\BotInterface;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\AppInterface;
use LbilTech\TelegramGitNotifier\Interfaces\EventInterface;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\SettingInterface;
use LbilTech\TelegramGitNotifier\Models\Event;
use LbilTech\TelegramGitNotifier\Models\Setting;
use LbilTech\TelegramGitNotifier\Structures\App;
use LbilTech\TelegramGitNotifier\Trait\BotSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\EventSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\EventTrait;
use Telegram;

class Bot implements AppInterface, BotInterface, EventInterface
{
    use App;
    use EventTrait;
    use BotSettingTrait;
    use EventSettingTrait;

    public Event $event;

    public Setting $setting;

    public function __construct(
        Telegram $telegram = null,
        ?string $chatBotId = null,
        Setting $setting = null,
        Event $event = null,
        ?string $settingFile = null,
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
    ) {
        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId($chatBotId);
        $this->event = $event ?? new Event();
        $this->setPlatFormForEvent($platform, $platformFile);

        $this->setting = $setting ?? new Setting();
        $this->updateSetting($settingFile);
    }

    public function updateSetting(?string $settingFile = null): void
    {
        if ($this->setting->getSettingFile()) {
            return;
        }
        $settingFile = $settingFile ?? config('telegram-git-notifier.data_file.setting');
        $this->setting->setSettingFile($settingFile);
        $this->setting->setSettingConfig();
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
