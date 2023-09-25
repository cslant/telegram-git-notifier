<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Exceptions\MessageIsEmptyException;

interface TelegramInterface
{
    /**
     * Set the menu button for a telegram
     *
     * @param array $menuCommand
     * @param string $menuTemplate
     *
     * @return void
     * @throws MessageIsEmptyException
     */
    public function setMyCommands(array $menuCommand,string $menuTemplate): void;
}
