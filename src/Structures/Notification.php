<?php

namespace LbilTech\TelegramGitNotifier\Structures;

use GuzzleHttp\Exception\GuzzleException;
use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Exceptions\SendNotificationException;
use Symfony\Component\HttpFoundation\Request;

trait Notification
{
    public mixed $payload;

    public string $message = '';

    public function accessDenied(
        string $chatId = null,
        string $viewTemplate = null,
    ): void {
        $this->telegram->sendMessage([
            'chat_id'                  => config('telegram-git-notifier.bot.chat_id'),
            'text'                     => view(
                $viewTemplate ?? config('telegram-git-notifier.view.globals.access_denied'),
                ['chatId' => $chatId]
            ),
            'disable_web_page_preview' => true,
            'parse_mode'               => 'HTML'
        ]);
    }

    public function setPayload(Request $request, string $event)
    {
        if ($this->event->platform === 'gitlab') {
            $this->payload = json_decode($request->getContent());
        } elseif ($this->event->platform === EventConstant::DEFAULT_PLATFORM) {
            $this->payload = json_decode($request->request->get('payload'));
        }
        $this->setMessage($event);

        return $this->payload;
    }

    /**
     * Set message from payload
     *
     * @param string $typeEvent
     *
     * @return void
     */
    private function setMessage(string $typeEvent): void
    {
        $event = tgn_event_name($typeEvent);
        $action = $this->getActionOfEvent($this->payload);

        $viewTemplate = empty($action)
            ? "events.{$this->event->platform}.{$event}.default"
            : "events.{$this->event->platform}.{$event}.{$action}";

        $this->message = view($viewTemplate, [
            'payload' => $this->payload,
            'event'   => tgn_convert_event_name($typeEvent),
        ]);
    }

    public function sendNotify(string $chatId, string $message = null): bool
    {
        if (!is_null($message)) {
            $this->message = $message;
        }

        $queryParams = [
            'chat_id'                  => $chatId,
            'disable_web_page_preview' => 1,
            'parse_mode'               => 'html',
            'text'                     => $this->message
        ];

        $url = 'https://api.telegram.org/bot'
            . config('telegram-git-notifier.bot.token') . '/sendMessage'
            . '?' . http_build_query($queryParams);

        try {
            $response = $this->client->request('GET', $url);

            if ($response->getStatusCode() === 200) {
                return true;
            }

            throw SendNotificationException::create();
        } catch (GuzzleException $e) {
            error_log($e->getMessage());
        }

        return false;
    }
}
