<?php

namespace LbilTech\TelegramGitNotifier\Models;

class Setting
{
    public array $settings = [];

    public string $settingFile = '';

    public function __construct()
    {
        if (file_exists($this->settingFile)) {
            $this->setSettingConfig();
        }
    }

    /**
     * Set settings
     *
     * @return void
     */
    private function setSettingConfig(): void
    {
        $json = file_get_contents($this->settingFile);
        $this->settings = json_decode($json, true);
    }

    /**
     * @return bool
     */
    public function allEventsNotifyStatus(): bool
    {
        if (!empty($this->settings) && $this->settings['all_events_notify'] === true) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isNotified(): bool
    {
        if (!empty($this->settings) && $this->settings['is_notified'] === true) {
            return true;
        }

        return false;
    }

    /**
     * Update setting item value and save to file
     *
     * @param string $settingName
     * @param $settingValue
     * @return bool
     */
    public function updateSettingItem(string $settingName, $settingValue = null): bool
    {
        $settingKeys = explode('.', $settingName);
        $lastKey = array_pop($settingKeys);
        $nestedSettings = &$this->settings;

        foreach ($settingKeys as $key) {
            if (!isset($nestedSettings[$key]) || !is_array($nestedSettings[$key])) {
                return false;
            }
            $nestedSettings = &$nestedSettings[$key];
        }

        if (isset($nestedSettings[$lastKey])) {
            $newValue = $settingValue ?? !$nestedSettings[$lastKey]; // if value is null, then toggle value
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
