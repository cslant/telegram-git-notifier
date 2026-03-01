<?php

namespace CSlant\TelegramGitNotifier\Objects;

use CSlant\TelegramGitNotifier\Models\Event;
use CSlant\TelegramGitNotifier\Models\Setting;
use CSlant\TelegramGitNotifier\Trait\ActionEventTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Validator
{
    use ActionEventTrait;

    private readonly Setting $setting;

    private readonly Event $event;

    private readonly LoggerInterface $logger;

    public function __construct(
        Setting $setting,
        Event $event,
        ?LoggerInterface $logger = null,
    ) {
        $this->setting = $setting;
        $this->event = $event;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * Validate whether the event is allowed before sending notification.
     *
     * @param string $platform Source code platform (github, gitlab)
     * @param string $event    Event name (push, pull_request, etc.)
     * @param object $payload  Webhook payload
     */
    public function isAccessEvent(
        string $platform,
        string $event,
        object $payload,
    ): bool {
        if (!$this->setting->isNotified()) {
            return false;
        }

        if ($this->setting->isAllEventsNotification()) {
            return true;
        }

        $this->event->setEventConfig($platform);

        $eventConfig = $this->event->getEventConfig()[tgn_convert_event_name($event)] ?? false;
        $action = $this->getActionOfEvent($payload);

        if (!empty($action) && isset($eventConfig[$action])) {
            $eventConfig = $eventConfig[$action];
        }

        if (is_array($eventConfig) || !$eventConfig) {
            $this->logger->debug('Event config not found for event: {event}', [
                'event' => $event,
                'platform' => $platform,
            ]);

            return false;
        }

        return (bool) $eventConfig;
    }
}
