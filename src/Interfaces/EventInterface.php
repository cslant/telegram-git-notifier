<?php

namespace CSlant\TelegramGitNotifier\Interfaces;

use CSlant\TelegramGitNotifier\Constants\EventConstant;
use CSlant\TelegramGitNotifier\Exceptions\ConfigFileException;
use CSlant\TelegramGitNotifier\Trait\ActionEventTrait;
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
    public function setPlatFormForEvent(?string $platform = EventConstant::DEFAULT_PLATFORM, ?string $platformFile = null): void;

    /**
     * Set event config and get event name
     *
     * @param Request $request
     *
     * @return string|null
     * @see EventTrait::handleEventFromRequest()
     */
    public function handleEventFromRequest(Request $request): ?string;

    /**
     * @return void
     * @throws ConfigFileException
     */
    public function validatePlatformFile(): void;
}
