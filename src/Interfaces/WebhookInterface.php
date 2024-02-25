<?php

namespace CSlant\TelegramGitNotifier\Interfaces;

use CSlant\TelegramGitNotifier\Exceptions\WebhookException;

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
     * @return string
     * @throws WebhookException
     */
    public function setWebhook(): string;

    /**
     * Delete webhook for telegram bot
     *
     * @return string
     * @throws WebhookException
     */
    public function deleteWebHook(): string;

    /**
     * Get webhook info
     *
     * @return string
     * @throws WebhookException
     */
    public function getWebHookInfo(): string;

    /**
     * Get webhook update
     *
     * @return string
     * @throws WebhookException
     */
    public function getUpdates(): string;
}
