<?php

namespace LbilTech\TelegramGitNotifier\Objects;

use LbilTech\TelegramGitNotifier\Models\Event;
use LbilTech\TelegramGitNotifier\Models\Setting;
use LbilTech\TelegramGitNotifier\Trait\ActionEventTrait;

class Validator
{
    use ActionEventTrait;

    private Setting $setting;

    private Event $event;

    public function __construct(Setting $setting, Event $event)
    {
        $this->setting = $setting;
        $this->event = $event;
    }

    /**
     * Validate is access event before send notify
     *
     * @param string $platform Source code platform (GitHub, GitLab)
     * @param string $event Event name (push, pull_request)
     * @param object $payload
     *
     * @return bool
     */
    public function isAccessEvent(
        string $platform,
        string $event,
        object $payload
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
            $eventConfig = false;
            error_log('\n Event config is not found \n');
        }

        return $eventConfig;
    }
}
