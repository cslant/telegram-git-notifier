<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Interfaces\EventInterface;
use LbilTech\TelegramGitNotifier\Models\Event;
use LbilTech\TelegramGitNotifier\Models\Setting;
use LbilTech\TelegramGitNotifier\Trait\ActionEventTrait;

class EventService implements EventInterface
{
    use ActionEventTrait;

    public Setting $setting;

    public Event $event;

    public function __construct(
        Setting $setting,
        Event $event
    ) {
        $this->setting = $setting;
        $this->event = $event;
    }

    public function validateAccessEvent(
        string $platform,
        string $event,
        $payload
    ): bool {
        if (!$this->setting->isNotified()) {
            return false;
        }

        if ($this->setting->isAllEventsNotification()) {
            return true;
        }

        $this->event->setEventConfig($platform);
        $eventConfig = $this->event->eventConfig;

        $eventConfig = $eventConfig[tgn_convert_event_name($event)] ?? false;
        $action = $this->getActionOfEvent($payload);

        if (!empty($action) && isset($eventConfig[$action])) {
            $eventConfig = $eventConfig[$action];
        }

        if (!$eventConfig) {
            error_log('\n Event config is not found \n');
        }

        return (bool)$eventConfig;
    }
}
