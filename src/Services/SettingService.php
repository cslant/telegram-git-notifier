<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Interfaces\SettingInterface;
use LbilTech\TelegramGitNotifier\Models\Setting;
use Telegram;

class SettingService extends AppService implements SettingInterface
{
    protected Setting $setting;

    protected Telegram $telegram;

    protected string $chatId;

    protected array $settingData = [];

    public function __construct(Telegram $telegram, string $chatId)
    {
        parent::__construct($telegram, $chatId);

        $this->setting = new Setting();
        $this->settingData = $this->setting->getSettings();
    }
}
