<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Interfaces\TelegramInterface;
use Telegram;

class TelegramService extends AppService implements TelegramInterface
{
    public Telegram $telegram;

    protected SettingService $settingService;

    public function __construct(Telegram $telegram, SettingService $settingService)
    {
        parent::__construct($telegram);

        $this->settingService = $settingService;
    }

    public function setMyCommands(
        array $menuCommand,
        string $menuTemplate
    ): void {
        $this->telegram->setMyCommands([
            'commands' => json_encode($menuCommand)
        ]);
        $this->sendMessage(view($menuTemplate));
    }
}
