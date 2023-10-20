<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Exceptions\InvalidViewTemplateException;
use LbilTech\TelegramGitNotifier\Exceptions\MessageIsEmptyException;
use LbilTech\TelegramGitNotifier\Trait\BotSettingTrait;
use LbilTech\TelegramGitNotifier\Trait\EventSettingTrait;

interface SettingInterface
{
    /**
     * Create markup for select event
     *
     * @param string|null $parentEvent
     * @param string $platform
     *
     * @return array
     * @see EventSettingTrait::eventMarkup()
     */
    public function eventMarkup(?string $parentEvent = null, string $platform = EventConstant::DEFAULT_PLATFORM): array;

    /**
     * Get callback data for markup
     *
     * @param string $event
     * @param string $platform
     * @param array|bool $value
     * @param string|null $parentEvent
     *
     * @return string
     * @see EventSettingTrait::getCallbackData()
     */
    public function getCallbackData(string $event, string $platform, array|bool $value = false, ?string $parentEvent = null): string;

    /**
     * Get event name for markup
     *
     * @param string $event
     * @param $value
     *
     * @return string
     * @see EventSettingTrait::getEventName()
     */
    public function getEventName(string $event, $value): string;

    /**
     * Get end keyboard buttons
     *
     * @param string $platform
     * @param string|null $parentEvent
     *
     * @return array
     * @see EventSettingTrait::getEndKeyboard()
     */
    public function getEndKeyboard(string $platform, ?string $parentEvent = null): array;

    /**
     * Handle event callback settings
     *
     * @param string|null $callback
     * @param string|null $platform
     *
     * @return void
     * @throws InvalidViewTemplateException
     * @see EventSettingTrait::eventHandle()
     */
    public function eventHandle(?string $callback = null, ?string $platform = null): void;
    /**
     * Get the platform from callback
     *
     * @param string|null $callback
     * @param string|null $platform
     *
     * @return string
     * @see EventSettingTrait::getPlatformFromCallback()
     */
    public function getPlatformFromCallback(?string $callback, ?string $platform): string;

    /**
     * First event settings
     *
     * @param string $platform
     * @param string|null $callback
     * @param string|null $view
     *
     * @return bool
     * @see EventSettingTrait::sendSettingEventMessage()
     * @throws InvalidViewTemplateException
     */
    public function sendSettingEventMessage(string $platform, ?string $callback = null, ?string $view = null): bool;

    /**
     * Get event name from callback
     *
     * @param string|null $callback
     *
     * @return string
     * @see EventSettingTrait::getEventFromCallback()
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
     * @see EventSettingTrait::handleEventWithActions()
     * @throws InvalidViewTemplateException
     */
    public function handleEventWithActions(
        string $event,
        string $platform,
        ?string $view = null
    ): bool;

    /**
     * Handle event update
     *
     * @param string $event
     * @param string $platform
     *
     * @return void
     * @see EventSettingTrait::handleEventUpdate()
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
     * @see EventSettingTrait::eventUpdateHandle()
     * @throws InvalidViewTemplateException
     */
    public function eventUpdateHandle(string $event, string $platform): void;

    /**
     * Send a setting message
     *
     * @param string|null $view
     *
     * @return void
     * @see BotSettingTrait::settingHandle()
     * @throws MessageIsEmptyException
     */
    public function settingHandle(?string $view = null): void;

    /**
     * Generate setting markup
     *
     * @return array[]
     * @see BotSettingTrait::settingMarkup()
     */
    public function settingMarkup(): array;

    /**
     * @param array $markup
     *
     * @return array
     * @see BotSettingTrait::customEventMarkup()
     */
    public function customEventMarkup(array $markup): array;
}
