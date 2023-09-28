<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Constants\SettingConstant;
use LbilTech\TelegramGitNotifier\Exceptions\EntryNotFoundException;
use LbilTech\TelegramGitNotifier\Exceptions\InvalidViewTemplateException;

interface EventInterface
{
    /**
     * Validate access event before send notify
     *
     * @param string $platform Source code platform (GitHub, GitLab)
     * @param string $event Event name (push, pull_request)
     * @param $payload
     *
     * @return bool
     */
    public function validateAccessEvent(
        string $platform,
        string $event,
        $payload
    ): bool;

    /**
     * Get action name of event from payload data
     *
     * @param $payload
     *
     * @return string
     * @see ActionEventTrait::getActionOfEvent()
     */
    public function getActionOfEvent($payload): string;

    /**
     * Create markup for select event
     *
     * @param string|null $parentEvent
     * @param string $platform
     *
     * @return array
     */
    public function eventMarkup(
        ?string $parentEvent = null,
        string $platform = EventConstant::DEFAULT_PLATFORM
    ): array;

    /**
     * Get callback data for markup
     *
     * @param string $event
     * @param string $platform
     * @param array|bool $value
     * @param string|null $parentEvent
     *
     * @return string
     */
    public function getCallbackData(
        string $event,
        string $platform,
        array|bool $value = false,
        ?string $parentEvent = null
    ): string;

    /**
     * Get event name for markup
     *
     * @param string $event
     * @param $value
     *
     * @return string
     */
    public function getEventName(string $event, $value): string;

    /**
     * Get end keyboard buttons
     *
     * @param string $platform
     * @param string|null $parentEvent
     *
     * @return array
     */
    public function getEndKeyboard(
        string $platform,
        ?string $parentEvent = null
    ): array;

    /**
     * Handle event callback settings
     *
     * @param string|null $callback
     * @param string|null $platform
     *
     * @return void
     * @throws EntryNotFoundException
     * @throws InvalidViewTemplateException
     */
    public function eventHandle(
        ?string $callback = null,
        ?string $platform = null
    ): void;

    /**
     * Get the platform from callback
     *
     * @param string|null $callback
     * @param string|null $platform
     *
     * @return string
     */
    public function getPlatformFromCallback(
        ?string $callback,
        ?string $platform
    ): string;

    /**
     * First event settings
     *
     * @param string $platform
     * @param string|null $callback
     * @param string|null $view
     *
     * @return bool
     * @throws EntryNotFoundException
     * @throws InvalidViewTemplateException
     */
    public function sendSettingEventMessage(
        string $platform,
        ?string $callback = null,
        ?string $view = SettingConstant::V_SETTING_EVENT
    ): bool;

    /**
     * Get event name from callback
     *
     * @param string|null $callback
     *
     * @return string
     */
    public function getEventFromCallback(?string $callback): string;

    /**
     * Handle event with actions
     *
     * @param string $event
     * @param string $platform
     * @param string|null $view
     *
     * @return bool
     * @throws EntryNotFoundException
     * @throws InvalidViewTemplateException
     */
    public function handleEventWithActions(
        string $event,
        string $platform,
        ?string $view = SettingConstant::V_SETTING_EVENT_ACTION
    ): bool;

    /**
     * Handle event update
     *
     * @param string $event
     * @param string $platform
     *
     * @return void
     * @throws EntryNotFoundException
     * @throws InvalidViewTemplateException
     */
    public function handleEventUpdate(string $event, string $platform): void;

    /**
     * Handle event update
     *
     * @param string $event
     * @param string $platform
     *
     * @return void
     * @throws EntryNotFoundException
     * @throws InvalidViewTemplateException
     */
    public function eventUpdateHandle(string $event, string $platform): void;
}
