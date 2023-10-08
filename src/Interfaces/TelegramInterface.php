<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Exceptions\MessageIsEmptyException;

interface TelegramInterface
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
}
