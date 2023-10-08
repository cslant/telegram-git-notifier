<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

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
}
