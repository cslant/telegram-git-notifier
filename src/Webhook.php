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

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
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
        $url = "https://api.telegram.org/bot{$this->token}/setWebhook?url={$this->url}";

        try {
            $response = $this->client->request('GET', $url);
            return $response->getBody()->getContents();
        } catch (GuzzleException) {
            throw WebhookException::set();
        }
    }

    public function deleteWebHook(): string
    {
        $url = "https://api.telegram.org/bot{$this->token}/deleteWebhook";

        try {
            $response = $this->client->request('GET', $url);
            return $response->getBody()->getContents();
        } catch (GuzzleException) {
            throw WebhookException::delete();
        }
    }

    public function getWebHookInfo(): string
    {
        $url = "https://api.telegram.org/bot{$this->token}/getWebhookInfo";

        try {
            $response = $this->client->request('GET', $url);
            return $response->getBody()->getContents();
        } catch (GuzzleException) {
            throw WebhookException::getWebHookInfo();
        }
    }

    public function getUpdates(): string
    {
        $url = "https://api.telegram.org/bot{$this->token}/getUpdates";

        try {
            $response = $this->client->request('GET', $url);
            return $response->getBody()->getContents();
        } catch (GuzzleException) {
            throw WebhookException::getUpdates();
        }
    }
}
