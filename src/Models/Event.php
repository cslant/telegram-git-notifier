<?php

namespace CSlant\TelegramGitNotifier\Models;

use CSlant\TelegramGitNotifier\Enums\Platform;

class Event
{
    /** @var array<string, mixed> */
    private array $eventConfig = [];

    private bool $dirty = false;

    private string $lastLoadedFile = '';

    public string $platform = Platform::DEFAULT;

    public string $platformFile = '' {
        get {
            return $this->platformFile;
        }
        set {
            $this->platformFile = $value;
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getEventConfig(): array
    {
        return $this->eventConfig;
    }

    /**
     * Set event config from JSON file with in-memory caching.
     * Only reads from disk if the file hasn't been loaded yet or platform changes.
     */
    public function setEventConfig(?string $platform = null): void
    {
        $newPlatform = $platform ?? Platform::DEFAULT;
        $this->platform = $newPlatform;

        // Skip re-reading if same file is already loaded
        if ($this->platformFile === $this->lastLoadedFile && $this->eventConfig !== []) {
            return;
        }

        $this->loadFromFile();
    }

    /**
     * Force reload event config from file.
     */
    public function reloadEventConfig(): void
    {
        $this->loadFromFile();
    }

    /**
     * Update event config by event and action.
     * Toggle the boolean value for the specified event/action.
     */
    public function updateEvent(string $event, ?string $action): void
    {
        if ($action !== null && $action !== '') {
            $this->eventConfig[$event][$action]
                = !$this->eventConfig[$event][$action];
        } else {
            $this->eventConfig[$event] = !$this->eventConfig[$event];
        }

        $this->dirty = true;
        $this->persist();
    }

    public function getPlatformEnum(): Platform
    {
        return Platform::from($this->platform);
    }

    private function loadFromFile(): void
    {
        if ($this->platformFile === '' || !file_exists($this->platformFile)) {
            $this->eventConfig = [];

            return;
        }

        $json = file_get_contents($this->platformFile);

        if ($json !== false && $json !== '') {
            $this->eventConfig = json_decode($json, true) ?? [];
        }

        $this->lastLoadedFile = $this->platformFile;
        $this->dirty = false;
    }

    private function persist(): void
    {
        if (!$this->dirty || !file_exists($this->platformFile)) {
            return;
        }

        $json = json_encode($this->eventConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->platformFile, $json, LOCK_EX);
        $this->dirty = false;
    }
}
