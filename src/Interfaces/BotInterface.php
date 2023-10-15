<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Exceptions\MessageIsEmptyException;

interface BotInterface
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
}
