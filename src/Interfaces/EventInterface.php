<?php

namespace LbilTech\TelegramGitNotifier\Interfaces;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Trait\ActionEventTrait;
use Symfony\Component\HttpFoundation\Request;

interface EventInterface
{
    /**
     * Get action name of event from payload data
     *
     * @param object $payload
     *
     * @return string
     * @see ActionEventTrait::getActionOfEvent()
     */
    public function getActionOfEvent(object $payload): string;

    /**
     * Set platform and platform file for event
     *
     * @param string|null $platform
     * @param string|null $platformFile
     *
     * @return void
     * @see EventTrait::setPlatFormForEvent()
     */
    public function setPlatFormForEvent(?string $platform = EventConstant::DEFAULT_PLATFORM, string $platformFile = null): void;

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
