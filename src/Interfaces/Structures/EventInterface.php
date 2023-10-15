<?php

namespace LbilTech\TelegramGitNotifier\Interfaces\Structures;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;

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
     * Set the platform file path
     *
     * @param string $platformFile
     *
     * @return void
     */
    public function setPlatformFile(string $platformFile): void;

    /**
     * Set event config
     *
     * @param string $platform
     *
     * @return void
     */
    public function setEventConfig(string $platform = EventConstant::DEFAULT_PLATFORM): void;

    /**
     * Update event config by event and action
     *
     * @param string $event
     * @param string|null $action
     *
     * @return void
     */
    public function updateEvent(string $event, string|null $action): void;

    /**
     * Get platform file path
     *
     * @return string
     */
    public function getPlatformFile(): string;
}
