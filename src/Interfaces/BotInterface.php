<?php

namespace CSlant\TelegramGitNotifier\Interfaces;

use CSlant\TelegramGitNotifier\Exceptions\ConfigFileException;
use CSlant\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
use CSlant\TelegramGitNotifier\Interfaces\Structures\AppInterface;

interface BotInterface extends AppInterface, EventInterface, SettingInterface
{
    /**
     * Set the menu button for a telegram
     *
     * @param array $menuCommand
     * @param string|null $view
     *
     * @return void
     * @throws MessageIsEmptyException
     */
    public function setMyCommands(
        array $menuCommand,
        ?string $view = null
    ): void;

    /**
     * Check callback from a telegram
     *
     * @return bool
     */
    public function isCallback(): bool;

    /**
     * Check message or command from a telegram
     *
     * @return bool
     */
    public function isMessage(): bool;

    /**
     * Check owner of a telegram
     *
     * @return bool
     */
    public function isOwner(): bool;

    /**
     * Check chat id from telegram permission with config
     * @return bool
     */
    public function isNotifyChat(): bool;

    /**
     * @return void
     * @throws ConfigFileException
     */
    public function validateSettingFile(): void;
}
