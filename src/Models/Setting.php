<?php

namespace CSlant\TelegramGitNotifier\Models;

use CSlant\TelegramGitNotifier\Constants\SettingConstant;

class Setting
{
    private array $settings = [];

    private string $settingFile = '';

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param string $settingFile
     *
     * @return void
     */
    public function setSettingFile(string $settingFile): void
    {
        $this->settingFile = $settingFile;
    }

    /**
     * @return string
     */
    public function getSettingFile(): string
    {
        return $this->settingFile;
    }

    /**
     * Set settings
     *
     * @return void
     */
    public function setSettingConfig(): void
    {
        $json = file_get_contents($this->settingFile);

        if (!empty($json)) {
            $this->settings = json_decode($json, true);
        }
    }

    /**
     * @return bool
     */
    public function isAllEventsNotification(): bool
    {
        if (!empty($this->settings)
            && $this->settings[SettingConstant::T_ALL_EVENTS_NOTIFICATION] === true
        ) {
            return true;
        }

        return false;
    }

    /**
     * Condition for checking the notification status
     *
     * @return bool
     */
    public function isNotified(): bool
    {
        if (!empty($this->settings)
            && $this->settings[SettingConstant::T_IS_NOTIFIED] === true
        ) {
            return true;
        }

        return false;
    }

    /**
     * Update setting item value and save to file
     *
     * @param string $settingName
     * @param array|string|bool|int|null $settingValue
     *
     * @return bool
     */
    public function updateSetting(
        string $settingName,
        mixed $settingValue = null
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
            $newValue = $settingValue ?? !$nestedSettings[$lastKey];
            $nestedSettings[$lastKey] = $newValue;

            return $this->saveSettingsToFile();
        }

        return false;
    }

    /**
     * Save settings to json file
     *
     * @return bool
     */
    private function saveSettingsToFile(): bool
    {
        if (file_exists($this->settingFile)) {
            $json = json_encode($this->settings, JSON_PRETTY_PRINT);
            file_put_contents($this->settingFile, $json, LOCK_EX);

            return true;
        }

        return false;
    }
}
