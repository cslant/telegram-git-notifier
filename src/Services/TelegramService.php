<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Interfaces\TelegramInterface;
use Telegram;

class TelegramService extends AppService implements TelegramInterface
{
    protected Telegram $telegram;

    protected string $chatId;

    protected SettingService $settingService;

    public function __construct(Telegram $telegram, string $chatId, SettingService $settingService)
    {
        parent::__construct($telegram, $chatId);

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
