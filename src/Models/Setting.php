<?php

namespace CSlant\TelegramGitNotifier\Models;

use CSlant\TelegramGitNotifier\Constants\SettingConstant;

class Setting
{
    /** @var array<string, mixed> */
    private array $settings = [];

    private string $settingFile = '';

    private bool $dirty = false;

    /**
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setSettingFile(string $settingFile): void
    {
        $this->settingFile = $settingFile;
    }

    public function getSettingFile(): string
    {
        return $this->settingFile;
    }

    /**
     * Load settings from JSON file with in-memory caching.
     */
    public function setSettingConfig(): void
    {
        if ($this->settings !== []) {
            return;
        }

        $this->loadFromFile();
    }

    /**
     * Force reload settings from file.
     */
    public function reloadSettings(): void
    {
        $this->loadFromFile();
    }

    public function isAllEventsNotification(): bool
    {
        return !empty($this->settings)
            && ($this->settings[SettingConstant::T_ALL_EVENTS_NOTIFICATION] ?? false) === true;
    }

    public function isNotified(): bool
    {
        return !empty($this->settings)
            && ($this->settings[SettingConstant::T_IS_NOTIFIED] ?? false) === true;
    }

    /**
     * Update setting item value and save to file.
     *
     * @param string $settingName Dot-notation key path
     * @param mixed $settingValue New value (null = toggle boolean)
     */
    public function updateSetting(
        string $settingName,
        mixed $settingValue = null,
    ): bool {
        $settingKeys = explode('.', $settingName);
        $lastKey = array_pop($settingKeys);
        $nestedSettings = &$this->settings;

        foreach ($settingKeys as $key) {
            if (!isset($nestedSettings[$key])
                || !is_array($nestedSettings[$key])
            ) {
                return false;
            }
            $nestedSettings = &$nestedSettings[$key];
        }

        if (isset($nestedSettings[$lastKey])) {
            $nestedSettings[$lastKey] = $settingValue ?? !$nestedSettings[$lastKey];
            $this->dirty = true;

            return $this->persist();
        }

        return false;
    }

    private function loadFromFile(): void
    {
        if (empty($this->settingFile) || !file_exists($this->settingFile)) {
            $this->settings = [];

            return;
        }

        $json = file_get_contents($this->settingFile);

        if (!empty($json)) {
            $this->settings = json_decode($json, true) ?? [];
        }

        $this->dirty = false;
    }

    private function persist(): bool
    {
        if (!$this->dirty || !file_exists($this->settingFile)) {
            return false;
        }

        $json = json_encode($this->settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($this->settingFile, $json, LOCK_EX);
        $this->dirty = false;

        return true;
    }
}
