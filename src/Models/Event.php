<?php

declare(strict_types=1);

namespace CSlant\TelegramGitNotifier\Models;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use JsonException;
use RuntimeException;

/**
 * Class Event
 * 
 * Handles event configuration management for different platforms.
 * Manages loading, updating, and persisting event configurations.
 */
class Event
{
    /** @var array<string, mixed> Event configuration data */
    private array $eventConfig = [];

    /** @var string The current platform (e.g., 'github', 'gitlab') */
    public string $platform = EventConstant::DEFAULT_PLATFORM;

    /** @var string Path to the platform configuration file */
    private string $platformFile = '';

    /**
     * Get the platform configuration file path
     */
    public function getPlatformFile(): string
    {
        return $this->platformFile;
    }

    /**
     * Set the platform configuration file path
     *
     * @throws RuntimeException If the file doesn't exist or is not readable
     */
    public function setPlatformFile(string $platformFile): void
    {
        if (!file_exists($platformFile) || !is_readable($platformFile)) {
            throw new RuntimeException(
                "Platform configuration file not found or not readable: {$platformFile}"
            );
        }
        
        $this->platformFile = $platformFile;
    }

    /**
     * Get the current event configuration
     *
     * @return array<string, mixed>
     */
    public function getEventConfig(): array
    {
        return $this->eventConfig;
    }

    /**
     * Load and set event configuration from platform file
     *
     * @param string|null $platform The platform to load configuration for
     * @throws RuntimeException If the configuration file cannot be read or parsed
     */
    public function setEventConfig(?string $platform = null): void
    {
        $this->platform = $platform ?? EventConstant::DEFAULT_PLATFORM;

        if (empty($this->platformFile)) {
            throw new RuntimeException('Platform file path is not set');
        }

        $json = file_get_contents($this->platformFile);
        if ($json === false) {
            throw new RuntimeException("Failed to read platform file: {$this->platformFile}");
        }

        if (empty($json)) {
            return;
        }

        try {
            $config = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $this->eventConfig = is_array($config) ? $config : [];
        } catch (JsonException $e) {
            throw new RuntimeException("Invalid JSON in platform file: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * Toggle an event or event action configuration
     *
     * @param string $event The event name
     * @param string|null $action The specific action within the event (optional)
     * @throws RuntimeException If unable to save the updated configuration
     */
    public function updateEvent(string $event, ?string $action = null): void
    {
        if ($action !== null && $action !== '') {
            if (!isset($this->eventConfig[$event][$action])) {
                $this->eventConfig[$event][$action] = false;
            }
            $this->eventConfig[$event][$action] = !$this->eventConfig[$event][$action];
        } else {
            if (!isset($this->eventConfig[$event])) {
                $this->eventConfig[$event] = false;
            } elseif (is_array($this->eventConfig[$event])) {
                // Toggle all actions if the event is an array of actions
                foreach ($this->eventConfig[$event] as &$value) {
                    $value = !$value;
                }
                unset($value);
            } else {
                $this->eventConfig[$event] = !$this->eventConfig[$event];
            }
        }

        $this->saveEventConfig();
    }

    /**
     * Save the current event configuration to the platform file
     *
     * @throws RuntimeException If unable to write to the configuration file
     */
    private function saveEventConfig(): void
    {
        if (empty($this->platformFile)) {
            throw new RuntimeException('Cannot save event config: Platform file path is not set');
        }

        if (!is_writable($this->platformFile)) {
            throw new RuntimeException("Cannot write to platform file: {$this->platformFile}");
        }

        try {
            $json = json_encode($this->eventConfig, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
            $result = file_put_contents($this->platformFile, $json, LOCK_EX);
            
            if ($result === false) {
                throw new RuntimeException("Failed to write to platform file: {$this->platformFile}");
            }
        } catch (JsonException $e) {
            throw new RuntimeException("Failed to encode event config: {$e->getMessage()}", 0, $e);
        }
    }
}
