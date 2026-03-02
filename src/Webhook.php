<?php

namespace CSlant\TelegramGitNotifier;

use CSlant\TelegramGitNotifier\Exceptions\WebhookException;
use CSlant\TelegramGitNotifier\Interfaces\WebhookInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Webhook implements WebhookInterface
{
    private string $token;

    private string $url;

    private readonly Client $client;

    public function __construct(?Client $client = null)
    {
        $this->client = $client ?? new Client();
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setWebhook(): string
    {
        return $this->callTelegramApi(
            "setWebhook?url={$this->url}",
            static fn () => WebhookException::set(),
        );
    }

    public function deleteWebHook(): string
    {
        return $this->callTelegramApi(
            'deleteWebhook',
            static fn () => WebhookException::delete(),
        );
    }

    public function getWebHookInfo(): string
    {
        return $this->callTelegramApi(
            'getWebhookInfo',
            static fn () => WebhookException::getWebHookInfo(),
        );
    }

    public function getUpdates(): string
    {
        return $this->callTelegramApi(
            'getUpdates',
            static fn () => WebhookException::getUpdates(),
        );
    }

    /**
     * Centralized Telegram API call with retry support.
     *
     * @param string $endpoint    API endpoint (after /bot{token}/)
     * @param callable(): WebhookException $exceptionFactory
     * @param int $maxRetries     Max retry attempts for rate limiting
     *
     * @throws WebhookException
     */
    private function callTelegramApi(string $endpoint, callable $exceptionFactory, int $maxRetries = 3): string
    {
        $url = "https://api.telegram.org/bot{$this->token}/{$endpoint}";
        $options = [
            'verify' => config('telegram-git-notifier.app.request_verify'),
        ];

        $attempt = 0;

        while (true) {
            try {
                $response = $this->client->request('GET', $url, $options);

                return $response->getBody()->getContents();
            } catch (GuzzleException $e) {
                if ($attempt < $maxRetries && str_contains($e->getMessage(), '429')) {
                    usleep(2 ** $attempt * 1_000_000);
                    $attempt++;

                    continue;
                }

                $baseException = $exceptionFactory();
                $message = $baseException->getMessage();

                if ($e->getMessage() !== '') {
                    $suffix = 'GuzzleException: ' . $e->getMessage();
                    $message = $message !== '' ? $message . ' | ' . $suffix : $suffix;
                }

                throw new WebhookException(
                    $message,
                    $baseException->getCode(),
                    $e
                );
            }
        }
    }
}
