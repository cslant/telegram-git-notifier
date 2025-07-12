<?php

declare(strict_types=1);

namespace CSlant\TelegramGitNotifier;

use CSlant\TelegramGitNotifier\Exceptions\WebhookException;
use CSlant\TelegramGitNotifier\Interfaces\WebhookInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * Class Webhook
 * 
 * Handles all webhook-related operations with the Telegram Bot API.
 * Implements WebhookInterface for managing Telegram webhooks.
 */
class Webhook implements WebhookInterface
{
    private const TELEGRAM_API_BASE_URL = 'https://api.telegram.org/bot';
    private const DEFAULT_TIMEOUT = 30;

    private string $token = '';
    private string $url = '';
    private Client $client;

    /**
     * Initialize the Webhook handler with a Guzzle HTTP client
     *
     * @param Client|null $client Optional Guzzle client instance
     */
    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client([
            'timeout' => self::DEFAULT_TIMEOUT,
            'http_errors' => false,
        ]);
    }

    /**
     * Set the Telegram bot token
     *
     * @param string $token The Telegram bot token
     * @return void
     * @throws \InvalidArgumentException If the token is empty
     */
    public function setToken(string $token): void
    {
        $token = trim($token);
        if (empty($token)) {
            throw new \InvalidArgumentException('Telegram bot token cannot be empty');
        }
        
        $this->token = $token;
    }

    /**
     * Set the webhook URL
     *
     * @param string $url The webhook URL to set
     * @return void
     * @throws \InvalidArgumentException If the URL is invalid
     */
    public function setUrl(string $url): void
    {
        $url = trim($url);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid webhook URL provided');
        }
        
        $this->url = $url;
    }

    /**
     * Set up the webhook with Telegram
     *
     * @param array<string, mixed> $params Additional parameters for the webhook
     * @return array<string, mixed> The Telegram API response
     * @throws WebhookException If the operation fails
     */
    public function setWebhook(array $params = []): array
    {
        $this->validateToken();
        
        $url = $this->buildUrl('setWebhook', ['url' => $this->url] + $params);
        return $this->sendRequest('POST', $url);
    }

    /**
     * Delete the current webhook
     *
     * @param bool $dropPendingUpdates Whether to drop all pending updates
     * @return array<string, mixed> The Telegram API response
     * @throws WebhookException If the operation fails
     */
    public function deleteWebHook(bool $dropPendingUpdates = false): array
    {
        $this->validateToken();
        
        $params = $dropPendingUpdates ? ['drop_pending_updates' => 'true'] : [];
        $url = $this->buildUrl('deleteWebhook', $params);
        
        return $this->sendRequest('GET', $url);
    }

    /**
     * Get information about the current webhook
     *
     * @return array<string, mixed> Webhook information
     * @throws WebhookException If the operation fails
     */
    public function getWebHookInfo(): array
    {
        $this->validateToken();
        $url = $this->buildUrl('getWebhookInfo');
        
        return $this->sendRequest('GET', $url);
    }

    /**
     * Get updates from the Telegram API
     *
     * @param array<string, mixed> $params Additional parameters for the request
     * @return array<string, mixed> The updates from Telegram
     * @throws WebhookException If the operation fails
     */
    public function getUpdates(array $params = []): array
    {
        $this->validateToken();
        $url = $this->buildUrl('getUpdates', $params);
        
        return $this->sendRequest('GET', $url);
    }

    /**
     * Build the full Telegram API URL
     *
     * @param string $method The Telegram Bot API method
     * @param array<string, mixed> $params Query parameters
     * @return string The complete URL
     */
    private function buildUrl(string $method, array $params = []): string
    {
        $url = self::TELEGRAM_API_BASE_URL . $this->token . '/' . $method;
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }

    /**
     * Send a request to the Telegram API
     *
     * @param string $method The HTTP method
     * @param string $url The URL to send the request to
     * @return array<string, mixed> The decoded JSON response
     * @throws WebhookException If the request fails or the response is invalid
     */
    private function sendRequest(string $method, string $url): array
    {
        try {
            $response = $this->client->request($method, $url, [
                'verify' => config('telegram-git-notifier.app.request_verify', true),
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return $this->handleResponse($response);
        } catch (GuzzleException $e) {
            throw WebhookException::create(
                "Failed to send request to Telegram API: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Handle the API response
     *
     * @param ResponseInterface $response The HTTP response
     * @return array<string, mixed> The decoded JSON response
     * @throws WebhookException If the response cannot be decoded or contains an error
     */
    private function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();
        
        if ($statusCode !== 200) {
            throw WebhookException::create(
                "Telegram API returned status code: {$statusCode}",
                $statusCode
            );
        }
        
        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            
            if (!is_array($data)) {
                throw new JsonException('Invalid JSON response');
            }
            
            if (isset($data['ok']) && $data['ok'] === false) {
                $errorCode = $data['error_code'] ?? 0;
                $description = $data['description'] ?? 'Unknown error';
                
                throw WebhookException::create(
                    "Telegram API error: {$description}",
                    $errorCode
                );
            }
            
            return $data;
        } catch (JsonException $e) {
            throw WebhookException::create(
                "Failed to decode Telegram API response: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Validate that the bot token is set
     *
     * @throws WebhookException If the token is not set
     */
    private function validateToken(): void
    {
        if (empty($this->token)) {
            throw WebhookException::create('Telegram bot token is not set');
        }
    }
}
