<?php

namespace LbilTech\TelegramGitNotifier;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Interfaces\BotInterface;
use LbilTech\TelegramGitNotifier\Interfaces\Structures\AppInterface;
use LbilTech\TelegramGitNotifier\Interfaces\EventInterface;
use LbilTech\TelegramGitNotifier\Models\Event;
use LbilTech\TelegramGitNotifier\Models\Setting;
use LbilTech\TelegramGitNotifier\Structures\App;
use LbilTech\TelegramGitNotifier\Structures\TelegramBot;
use LbilTech\TelegramGitNotifier\Trait\BotSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\EventSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\EventTrait;
use Telegram;

class Bot implements AppInterface, BotInterface, EventInterface
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
}
