<?php

namespace LbilTech\TelegramGitNotifier\Structures;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;

trait Event
{
    public array $eventConfig = [];

    public string $platform = EventConstant::DEFAULT_PLATFORM;

    private string $platformFile = '';

    public function getPlatformFile(): string
    {
        return $this->platformFile;
    }

    public function setPlatformFile(string $platformFile): void
    {
        $this->platformFile = $platformFile;
    }

    public function setEventConfig(
        string $platform = EventConstant::DEFAULT_PLATFORM
    ): void {
        $this->platform = $platform;

        $json = file_get_contents($this->platformFile);
        $this->eventConfig = json_decode($json, true);
    }

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