<?php

namespace LbilTech\TelegramGitNotifier\Trait;

trait ValidationEventTrait
{
    public function validateAccessEvent(
        string $platform,
        string $event,
        $payload
    ): bool {
        if (!$this->isNotified()) {
            return false;
        }

        if ($this->isAllEventsNotification()) {
            return true;
        }

        $this->setEventConfig($platform);

        $eventConfig = $this->eventConfig[tgn_convert_event_name($event)] ?? false;
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
