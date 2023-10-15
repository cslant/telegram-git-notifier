<?php

namespace LbilTech\TelegramGitNotifier\Interfaces\Structures;

interface WebhookInterface
{
    /**
     * Set the telegram bot token
     *
     * @param string $token
     *
     * @return void
     */
    public function setToken(string $token): void;

    /**
     * Set app url
     *
     * @param string $url
     *
     * @return void
     */
    public function setUrl(string $url): void;

    /**
     * Set webhook for telegram bot
     *
     * @return false|string
     */
    public function setWebhook(): false|string;

    /**
     * Delete webhook for telegram bot
     *
     * @return false|string
     */
    public function deleteWebHook(): false|string;
}
