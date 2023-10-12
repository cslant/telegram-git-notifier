<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Interfaces\SettingInterface;
use LbilTech\TelegramGitNotifier\Models\Event;
use LbilTech\TelegramGitNotifier\Models\Setting;
use LbilTech\TelegramGitNotifier\Trait\BotSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\EventSettingTrait;
use Telegram;

class SettingService extends AppService implements SettingInterface
{
    use EventSettingTrait;
    use BotSettingTrait;

    public Setting $setting;

    public Event $event;

    public Telegram $telegram;

    public function __construct(
        Telegram $telegram,
        Setting $setting,
        Event $event
    ) {
        parent::__construct($telegram);

        $this->setting = $setting;
        $this->event = $event;
    }
}
