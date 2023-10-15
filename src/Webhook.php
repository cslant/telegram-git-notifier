<?php

namespace LbilTech\TelegramGitNotifier;

use LbilTech\TelegramGitNotifier\Interfaces\Structures\WebhookInterface;

class Webhook implements WebhookInterface
{
    private string $token;

    private string $url;

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setWebhook(): false|string
    {
        $url = "https://api.telegram.org/bot{$this->token}/setWebhook?url={$this->url}";

        return file_get_contents($url);
    }

    public function deleteWebHook(): false|string
    {
        $url = "https://api.telegram.org/bot{$this->token}/deleteWebhook";

        return file_get_contents($url);
    }
}
