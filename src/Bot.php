<?php

namespace CSlant\TelegramGitNotifier;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Interfaces\BotInterface;
use CSlant\TelegramGitNotifier\Interfaces\EventInterface;
use CSlant\TelegramGitNotifier\Interfaces\SettingInterface;
use CSlant\TelegramGitNotifier\Interfaces\Structures\AppInterface;
use CSlant\TelegramGitNotifier\Models\Event;
use CSlant\TelegramGitNotifier\Models\Setting;
use CSlant\TelegramGitNotifier\Structures\App;
use CSlant\TelegramGitNotifier\Structures\TelegramBot;
use CSlant\TelegramGitNotifier\Trait\BotSettingTrait;
use CSlant\TelegramGitNotifier\Trait\EventSettingTrait;
use CSlant\TelegramGitNotifier\Trait\EventTrait;
use Telegram;

class Bot implements AppInterface, BotInterface, EventInterface, SettingInterface
{
    use App;
    use TelegramBot;

    use EventTrait;
    use BotSettingTrait;
    use EventSettingTrait;

    public Event $event;

    public Setting $setting;

    public function __construct(
        Telegram $telegram = null,
        ?string $chatBotId = null,
        Event $event = null,
        ?string $platform = EventConstant::DEFAULT_PLATFORM,
        ?string $platformFile = null,
        Setting $setting = null,
        ?string $settingFile = null,
    ) {
        $this->telegram = $telegram ?? new Telegram(config('telegram-git-notifier.bot.token'));
        $this->setCurrentChatBotId($chatBotId);
        $this->event = $event ?? new Event();
        $this->setPlatFormForEvent($platform, $platformFile);

        $this->setting = $setting ?? new Setting();
        $this->updateSetting($settingFile);
    }
}
