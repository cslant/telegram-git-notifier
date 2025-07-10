<?php

declare(strict_types=1);

namespace CSlant\TelegramGitNotifier\Structures;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
use CSlant\TelegramGitNotifier\Exceptions\SendNotificationException;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait Notification
 * 
 * Handles notification-related functionality including message formatting
 * and sending notifications to Telegram.
 */
trait Notification
{
    /** @var object The payload received from the webhook */
    public object $payload;

    /** @var string The formatted message to be sent */
    public string $message = '';

    /**
     * Send an access denied message to the chat
     *
     * @param string|null $chatId The chat ID to send the message to
     * @param string|null $viewTemplate Custom view template to use
     * @throws SendNotificationException If the message cannot be sent
     */
    public function accessDenied(
        ?string $chatId = null,
        ?string $viewTemplate = null
    ): void {
        try {
            $template = $viewTemplate ?? config('telegram-git-notifier.view.globals.access_denied');
            $message = tgn_view($template, ['chatId' => $chatId]);

            if (empty($message)) {
                throw new MessageIsEmptyException('Access denied message template is empty');
            }

            $this->telegram->sendMessage([
                'chat_id' => $this->chatBotId,
                'text' => $message,
                'disable_web_page_preview' => true,
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            throw SendNotificationException::create('Failed to send access denied message', 0, $e);
        }
    }

    /**
     * Set the payload from the incoming request
     *
     * @param Request $request The HTTP request
     * @param string $event The event type
     * @return object The parsed payload
     * @throws MessageIsEmptyException If the payload is empty or invalid
     */
    public function setPayload(Request $request, string $event): object
    {
        $content = $this->extractContentFromRequest($request);
        
        if ($content === null) {
            throw new MessageIsEmptyException('No content found in the request');
        }

        try {
            $this->payload = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
            $this->setMessage($event);
            
            return $this->payload;
        } catch (JsonException $e) {
            throw new MessageIsEmptyException('Invalid JSON payload: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Extract content from the request based on the platform
     */
    private function extractContentFromRequest(Request $request): ?string
    {
        if ($this->event->platform === 'gitlab') {
            return $request->getContent() ?: null;
        }
        
        if ($this->event->platform === EventConstant::DEFAULT_PLATFORM) {
            return $request->request->get('payload');
        }
        
        return null;
    }

    /**
     * Set the message from the payload using the appropriate template
     *
     * @param string $eventType The type of event
     * @throws MessageIsEmptyException If the message cannot be generated
     */
    private function setMessage(string $eventType): void
    {
        $event = tgn_event_name($eventType);
        $action = $this->getActionOfEvent($this->payload);
        
        $viewTemplate = $this->buildViewTemplate($event, $action);
        $viewData = [
            'payload' => $this->payload,
            'event' => tgn_convert_event_name($eventType),
        ];

        $viewResult = tgn_view($viewTemplate, $viewData);

        if ($viewResult === null) {
            throw MessageIsEmptyException::create(
                "Failed to generate message for event: {$eventType}"
            );
        }

        $this->message = $viewResult;
    }

    /**
     * Build the view template path based on event and action
     */
    private function buildViewTemplate(string $event, ?string $action): string
    {
        $template = "events.{$this->event->platform}.{$event}";
        
        return $action 
            ? "{$template}.{$action}" 
            : "{$template}.default";
    }

    /**
     * Send a notification to Telegram
     *
     * @param string|null $message The message to send (uses stored message if null)
     * @param array<string, mixed> $options Additional options for the Telegram API
     * @return bool True if the notification was sent successfully
     * @throws SendNotificationException If the notification fails to send
     */
    public function sendNotify(?string $message = null, array $options = []): bool
    {
        $this->message = $message ?? $this->message;

        if (trim($this->message) === (config('telegram-git-notifier.view.ignore-message') ?? '')) {
            return false;
        }

        try {
            $queryParams = array_merge(
                $this->createTelegramBaseContent(),
                ['text' => $this->message],
                $options
            );

            $response = $this->sendTelegramRequest($queryParams);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            throw new SendNotificationException(
                'Failed to send notification: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Send a request to the Telegram API
     *
     * @param array<string, mixed> $queryParams The query parameters to send
     * @return \Psr\Http\Message\ResponseInterface The API response
     * @throws \RuntimeException If the request fails
     */
    private function sendTelegramRequest(array $queryParams): \Psr\Http\Message\ResponseInterface
    {
        $url = 'https://api.telegram.org/bot' . config('telegram-git-notifier.bot.token') . '/sendMessage';
        
        try {
            return $this->client->request('POST', $url, [
                'form_params' => $queryParams,
                'verify' => config('telegram-git-notifier.app.request_verify', true),
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException(
                'Failed to send request to Telegram API: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
