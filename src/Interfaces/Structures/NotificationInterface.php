<?php

namespace LbilTech\TelegramGitNotifier\Interfaces\Structures;

use LbilTech\TelegramGitNotifier\Exceptions\InvalidViewTemplateException;
use LbilTech\TelegramGitNotifier\Exceptions\SendNotificationException;
use Symfony\Component\HttpFoundation\Request;

interface NotificationInterface
{
    /**
     * Notify access denied to other chat ids
     *
     * @param string|null $viewTemplate
     * @param string|null $chatId
     *
     * @return void
     * @throws InvalidViewTemplateException
     * @see Notification::accessDenied()
     */
    public function accessDenied(
        string $chatId = null,
        string $viewTemplate = null,
    ): void;

    /**
     * Set payload from request
     *
     * @param Request $request
     * @param string $event
     *
     * @return mixed|void
     * @throws InvalidViewTemplateException
     * @see Notification::setPayload()
     */
    public function setPayload(Request $request, string $event);

    /**
     * Send notification to telegram
     *
     * @param string|null $message
     * @param array $options
     *
     * @return bool
     * @throws SendNotificationException
     * @see Notification::sendNotify()
     */
    public function sendNotify(string $message = null, array $options = []): bool;

    /**
     * Get action name of event from payload data
     *
     * @param object $payload
     *
     * @return string
     * @see EventTrait::getActionOfEvent()
     */
    public function getActionOfEvent(object $payload): string;

    /**
     * Convert chat and thread ids to array
     * Example: 1234567890;1234567890:thread1;1234567890:thread1,thread2
     * @return array
     */
    public function parseNotifyChatIds(): array;
}
