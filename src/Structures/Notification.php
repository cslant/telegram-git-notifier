<?php

namespace CSlant\TelegramGitNotifier\Structures;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
use CSlant\TelegramGitNotifier\Exceptions\SendNotificationException;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;

trait Notification
{
    public object $payload;

    public string $message = '';

    public function accessDenied(
        ?string $chatId = null,
        ?string $viewTemplate = null,
    ): void {
        $this->telegram->sendMessage([
            'chat_id' => $this->chatBotId,
            'text' => tgn_view(
                $viewTemplate ?? config('telegram-git-notifier.view.globals.access_denied'),
                ['chatId' => $chatId]
            ),
            'disable_web_page_preview' => true,
            'parse_mode' => 'HTML',
        ]);
    }

    /**
     * Parse webhook payload from request and build notification message.
     *
     * @return object|null The parsed payload object, or null if content is empty.
     *
     * @throws MessageIsEmptyException
     */
    public function setPayload(Request $request, string $event): ?object
    {
        $content = match ($this->event->platform) {
            'gitlab' => $request->getContent(),
            EventConstant::DEFAULT_PLATFORM => $request->request->get('payload'),
            default => null,
        };

        if (is_string($content) && $content !== '') {
            $this->payload = json_decode($content);
        }

        if (!isset($this->payload)) {
            return null;
        }

        $this->setMessage($event);

        return $this->payload;
    }

    /**
     * Build notification message from event template.
     *
     * @throws MessageIsEmptyException
     */
    private function setMessage(string $typeEvent): void
    {
        $event = tgn_event_name($typeEvent);
        $action = $this->getActionOfEvent($this->payload);

        $viewTemplate = $action !== ''
            ? "events.{$this->event->platform}.{$event}.{$action}"
            : "events.{$this->event->platform}.{$event}.default";

        $viewResult = tgn_view($viewTemplate, [
            'payload' => $this->payload,
            'event' => tgn_convert_event_name($typeEvent),
        ]);

        if ($viewResult === null) {
            throw MessageIsEmptyException::create();
        }

        $this->message = $viewResult;
    }

    /**
     * Send notification message to Telegram.
     *
     * @param array<string, mixed> $options
     *
     * @throws SendNotificationException
     */
    #[\NoDiscard('The return value indicates whether the notification was sent successfully')]
    public function sendNotify(?string $message = null, array $options = []): bool
    {
        if ($message !== null && $message !== '') {
            $this->message = $message;
        }

        $ignoreMessage = config('telegram-git-notifier.view.ignore-message');
        if (trim($this->message) === $ignoreMessage) {
            return false;
        }

        $queryParams = array_merge(
            $this->createTelegramBaseContent(),
            ['text' => $this->message],
            $options,
        );

        $url = 'https://api.telegram.org/bot'
            . config('telegram-git-notifier.bot.token')
            . '/sendMessage';

        $requestOptions = [
            'form_params' => $queryParams,
            'verify' => config('telegram-git-notifier.app.request_verify'),
        ];

        return $this->sendWithRetry($url, $requestOptions);
    }

    /**
     * Send HTTP request with exponential backoff retry for rate limits.
     *
     * @param array<string, mixed> $options
     *
     * @throws SendNotificationException
     */
    private function sendWithRetry(string $url, array $options, int $maxRetries = 3): bool
    {
        $attempt = 0;

        while (true) {
            try {
                $response = $this->client->request('POST', $url, $options);

                if ($response->getStatusCode() === 200) {
                    return true;
                }

                $body = (string) $response->getBody();

                // Telegram returns 429 for rate limiting
                if ($response->getStatusCode() === 429 && $attempt < $maxRetries) {
                    $retryAfter = json_decode($body, true)['parameters']['retry_after'] ?? (2 ** $attempt);
                    usleep((int) ($retryAfter * 1_000_000));
                    $attempt++;

                    continue;
                }

                throw SendNotificationException::create($body);
            } catch (GuzzleException $e) {
                if ($attempt < $maxRetries && str_contains($e->getMessage(), '429')) {
                    usleep(2 ** $attempt * 1_000_000);
                    $attempt++;

                    continue;
                }

                throw SendNotificationException::create($e->getMessage());
            }
        }
    }
}
