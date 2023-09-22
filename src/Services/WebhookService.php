<?php

namespace LbilTech\TelegramGitNotifier\Services;

class WebhookService
{
    private string $token;

    private string $url;

    /**
     * @param string $token
     *
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * Set webhook for telegram bot
     *
     * @return false|string
     */
    public function set(): false|string
    {
        $url = "https://api.telegram.org/bot{$this->token}/setWebhook?url={$this->url}";

        return file_get_contents($url);
    }

    /**
     * Delete webhook for telegram bot
     *
     * @return false|string
     */
    public function delete(): false|string
    {
        $url = "https://api.telegram.org/bot{$this->token}/deleteWebhook";

        return file_get_contents($url);
    }
}
