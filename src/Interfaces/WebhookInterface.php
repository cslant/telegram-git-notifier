<?php

declare(strict_types=1);

namespace CSlant\TelegramGitNotifier\Interfaces;

use CSlant\TelegramGitNotifier\Exceptions\WebhookException;

/**
 * Interface WebhookInterface
 * 
 * Defines the contract for handling Telegram webhook operations.
 * Any class implementing this interface must provide methods for managing
 * Telegram bot webhooks and retrieving updates.
 */
interface WebhookInterface
{
    /**
     * Set the Telegram bot token
     *
     * @param string $token The Telegram bot token
     * @return void
     * @throws \InvalidArgumentException If the token is empty
     */
    public function setToken(string $token): void;

    /**
     * Set the webhook URL
     *
     * @param string $url The webhook URL to set
     * @return void
     * @throws \InvalidArgumentException If the URL is invalid
     */
    public function setUrl(string $url): void;

    /**
     * Set up the webhook with Telegram
     *
     * @param array<string, mixed> $params Additional parameters for the webhook
     * @return array<string, mixed> The Telegram API response
     * @throws WebhookException If the operation fails
     */
    public function setWebhook(array $params = []): array;

    /**
     * Delete the current webhook
     *
     * @param bool $dropPendingUpdates Whether to drop all pending updates
     * @return array<string, mixed> The Telegram API response
     * @throws WebhookException If the operation fails
     */
    public function deleteWebHook(bool $dropPendingUpdates = false): array;

    /**
     * Get information about the current webhook
     *
     * @return array<string, mixed> Webhook information
     * @throws WebhookException If the operation fails
     */
    public function getWebHookInfo(): array;

    /**
     * Get updates from the Telegram API
     *
     * @param array<string, mixed> $params Additional parameters for the request
     * @return array<string, mixed> The updates from Telegram
     * @throws WebhookException If the operation fails
     */
    public function getUpdates(array $params = []): array;
}
