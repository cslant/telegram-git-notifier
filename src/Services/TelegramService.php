<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Interfaces\TelegramInterface;
use Telegram;

class TelegramService extends AppService implements TelegramInterface
{
    public Telegram $telegram;

    public function __construct(
        Telegram $telegram
    ) {
        parent::__construct($telegram);
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
}
