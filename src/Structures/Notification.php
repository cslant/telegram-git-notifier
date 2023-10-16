<?php

namespace LbilTech\TelegramGitNotifier\Structures;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
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
        if ($this->platform === 'gitlab') {
            $this->payload = json_decode($request->getContent());
        } elseif ($this->platform === EventConstant::DEFAULT_PLATFORM) {
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
            ? "events.{$this->platform}.{$event}.default"
            : "events.{$this->platform}.{$event}.{$action}";

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

        try {
            $this->sendMessage($this->message, [
                'chat_id' => $chatId,
            ]);

            throw SendNotificationException::create();
        } catch (MessageIsEmptyException $e) {
            error_log($e->getMessage());
        }

        return false;
    }
}
