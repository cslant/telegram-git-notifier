<?php

namespace CSlant\TelegramGitNotifier\Structures;

use GuzzleHttp\Exception\GuzzleException;
use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
use CSlant\TelegramGitNotifier\Exceptions\SendNotificationException;
use Symfony\Component\HttpFoundation\Request;

trait Notification
{
    public object $payload;

    public string $message = '';

    public function accessDenied(
        string $chatId = null,
        string $viewTemplate = null,
    ): void {
        $this->telegram->sendMessage([
            'chat_id' => $this->chatBotId,
            'text' => view(
                $viewTemplate ?? config('telegram-git-notifier.view.globals.access_denied'),
                ['chatId' => $chatId]
            ),
            'disable_web_page_preview' => true,
            'parse_mode' => 'HTML',
        ]);
    }

    public function setPayload(Request $request, string $event)
    {
        $content = null;

        if ($this->event->platform === 'gitlab') {
            $content = $request->getContent();
        } elseif ($this->event->platform === EventConstant::DEFAULT_PLATFORM) {
            $content = $request->request->get('payload');
        }

        if (is_string($content)) {
            $this->payload = json_decode($content);
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
     * @throws MessageIsEmptyException
     */
    private function setMessage(string $typeEvent): void
    {
        $event = tgn_event_name($typeEvent);
        $action = $this->getActionOfEvent($this->payload);

        $viewTemplate = empty($action)
            ? "events.{$this->event->platform}.{$event}.default"
            : "events.{$this->event->platform}.{$event}.{$action}";

        $viewResult = view($viewTemplate, [
            'payload' => $this->payload,
            'event' => tgn_convert_event_name($typeEvent),
        ]);

        if ($viewResult === null) {
            throw MessageIsEmptyException::create();
        }

        $this->message = $viewResult;
    }

    public function sendNotify(string $message = null, array $options = []): bool
    {
        $this->message = !empty($message) ? $message : $this->message;

        $queryParams = array_merge($this->createTelegramBaseContent(), ['text' => $this->message], $options);

        $url = 'https://api.telegram.org/bot' . config('telegram-git-notifier.bot.token') . '/sendMessage';

        try {
            $response = $this->client->request('POST', $url, [
                'form_params' => $queryParams,
            ]);

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
