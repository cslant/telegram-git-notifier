<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use Symfony\Component\HttpFoundation\Request;

interface EventInterface
{
//    /**
//     * Validate access event before send notify
//     *
//     * @param string $platform Source code platform (GitHub, GitLab)
//     * @param string $event Event name (push, pull_request)
//     * @param $payload
//     *
//     * @return bool
//     */
//    public function validateAccessEvent(
//        string $platform,
//        string $event,
//        $payload
//    ): bool;

    /**
     * Get action name of event from payload data
     *
     * @param $payload
     *
     * @return string
     * @see EventTrait::getActionOfEvent()
     */
    public function getActionOfEvent($payload): string;

    /**
     * Set platform and platform file for event
     *
     * @param string $platform
     * @param string|null $platformFile
     *
     * @return void
     * @see EventTrait::setPlatFormForEvent()
     */
    public function setPlatFormForEvent(string $platform, string $platformFile = null): void;

    /**
     * Set event config and get event name
     *
     * @param Request $request
     *
     * @return string|null
     * @see EventTrait::handleEventFromRequest()
     */
    public function handleEventFromRequest(Request $request): ?string;
}
