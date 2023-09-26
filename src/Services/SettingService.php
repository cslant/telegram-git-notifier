<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Interfaces\SettingInterface;
use LbilTech\TelegramGitNotifier\Models\Setting;
use Telegram;

class SettingService extends AppService implements SettingInterface
{
    protected Setting $setting;

    public Telegram $telegram;

    protected array $settingData = [];

    public function __construct(Telegram $telegram)
    {
        parent::__construct($telegram);

        $this->setting = new Setting();
        $this->settingData = $this->setting->getSettings();
    }
}
