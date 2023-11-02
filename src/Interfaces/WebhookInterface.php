<?php

namespace CSlant\TelegramGitNotifier\Interfaces;

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

    /**
     * Get webhook info
     *
     * @return false|string
     */
    public function getWebHookInfo(): false|string;

    /**
     * Get webhook update
     *
     * @return false|string
     */
    public function getUpdates(): false|string;
}
