<?php

namespace LbilTech\TelegramGitNotifier\Models;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;

class Event
{
    public array $eventConfig = [];

    public string $platform = EventConstant::DEFAULT_PLATFORM;

    public string $platformFile = '';

    public function __construct()
    {
        if (file_exists($this->platformFile)) {
            $this->setEventConfig();
        }
    }

    /**
     * Set event config
     *
     * @param string $platform
     *
     * @return void
     */
    public function setEventConfig(
        string $platform = EventConstant::DEFAULT_PLATFORM
    ): void {
        $this->platform = $platform;

        $json = file_get_contents($this->platformFile);
        $this->eventConfig = json_decode($json, true);
    }

    /**
     * Update event config by event and action
     *
     * @param string $event
     * @param string|null $action
     *
     * @return void
     */
    public function updateEvent(string $event, string|null $action): void
    {
        if (!empty($action)) {
            $this->eventConfig[$event][$action]
                = !$this->eventConfig[$event][$action];
        } else {
            $this->eventConfig[$event] = !$this->eventConfig[$event];
        }

        $this->saveEventConfig();
    }

    /**
     * Save event config
     *
     * @return void
     */
    private function saveEventConfig(): void
    {
        $jsonFile = $this->platformFile;
        if (file_exists($jsonFile)) {
            $json = json_encode($this->eventConfig, JSON_PRETTY_PRINT);
            file_put_contents($jsonFile, $json, LOCK_EX);
        }
    }
}
