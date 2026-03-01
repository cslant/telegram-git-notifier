<?php

namespace CSlant\TelegramGitNotifier\Models;

use CSlant\TelegramGitNotifier\Constants\EventConstant;

class Event
{
    private array $eventConfig = [];

    public string $platform = EventConstant::DEFAULT_PLATFORM;

    public string $platformFile = '' {
        get {
            return $this->platformFile;
        }
        set {
            $this->platformFile = $value;
        }
    }

    /**
     * @return array
     */
    public function getEventConfig(): array
    {
        return $this->eventConfig;
    }

    /**
     * Set event config
     *
     * @param string|null $platform
     *
     * @return void
     */
    public function setEventConfig(
        string $platform = null
    ): void {
        $this->platform = $platform ?? EventConstant::DEFAULT_PLATFORM;

        $json = file_get_contents($this->platformFile);

        if (!empty($json)) {
            $this->eventConfig = json_decode($json, true);
        }
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
        if (file_exists($this->platformFile)) {
            $json = json_encode($this->eventConfig, JSON_PRETTY_PRINT);
            file_put_contents($this->platformFile, $json, LOCK_EX);
        }
    }
}
