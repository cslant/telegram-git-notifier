<?php

namespace LbilTech\TelegramGitNotifier\Services;

use LbilTech\TelegramGitNotifier\Constants\EventConstant;
use LbilTech\TelegramGitNotifier\Constants\SettingConstant;
use LbilTech\TelegramGitNotifier\Interfaces\EventInterface;
use LbilTech\TelegramGitNotifier\Models\Event;
use LbilTech\TelegramGitNotifier\Models\Setting;
use Telegram;

class EventService extends AppService implements EventInterface
{
    protected Setting $setting;

    protected Event $event;

    protected Telegram $telegram;

    protected string $chatId;

    public function __construct(
        Telegram $telegram,
        string $chatId
    ) {
        parent::__construct($telegram, $chatId);

        $this->setting = new Setting();
        $this->event = new Event();
    }

    public function validateAccessEvent(
        string $platform,
        string $event,
        $payload
    ): bool {
        if (!$this->setting->isNotified()) {
            return false;
        }

        if ($this->setting->isAllEventsNotification()) {
            return true;
        }

        $this->event->setEventConfig($platform);
        $eventConfig = $this->event->eventConfig;

        $eventConfig = $eventConfig[tgn_convert_event_name($event)] ?? false;
        $action = $this->getActionOfEvent($payload);

        if (!empty($action) && isset($eventConfig[$action])) {
            $eventConfig = $eventConfig[$action];
        }

        if (!$eventConfig) {
            error_log('\n Event config is not found \n');
        }

        return (bool)$eventConfig;
    }

    public function getActionOfEvent($payload): string
    {
        $action = $payload?->action
            ?? $payload?->object_attributes?->action
            ?? $payload?->object_attributes?->noteable_type
            ?? '';

        if (!empty($action)) {
            return tgn_convert_action_name($action);
        }

        return '';
    }

    public function eventMarkup(
        ?string $parentEvent = null,
        string $platform = EventConstant::DEFAULT_PLATFORM
    ): array {
        $replyMarkup = $replyMarkupItem = [];

        $this->event->setEventConfig($platform);
        $events = $parentEvent === null ? $this->event->eventConfig
            : $this->event->eventConfig[$parentEvent];

        foreach ($events as $key => $value) {
            if (count($replyMarkupItem)
                === SettingConstant::BTN_LINE_ITEM_COUNT
            ) {
                $replyMarkup[] = $replyMarkupItem;
                $replyMarkupItem = [];
            }

            $callbackData = $this->getCallbackData(
                $key,
                $platform,
                $value,
                $parentEvent
            );
            $eventName = $this->getEventName($key, $value);

            $replyMarkupItem[] = $this->telegram->buildInlineKeyBoardButton(
                $eventName,
                '',
                $callbackData
            );
        }

        if (count($replyMarkupItem) > 0) {
            $replyMarkup[] = $replyMarkupItem;
        }

        $replyMarkup[] = $this->getEndKeyboard($platform, $parentEvent);

        return $replyMarkup;
    }

    public function getCallbackData(
        string $event,
        string $platform,
        array|bool $value = false,
        ?string $parentEvent = null
    ): string {
        $platformSeparator = $platform === EventConstant::DEFAULT_PLATFORM
            ? EventConstant::GITHUB_EVENT_SEPARATOR
            : EventConstant::GITLAB_EVENT_SEPARATOR;
        $prefix = EventConstant::EVENT_PREFIX . $platformSeparator;

        if (is_array($value)) {
            return $prefix . EventConstant::EVENT_HAS_ACTION_SEPARATOR . $event;
        } elseif ($parentEvent) {
            return $prefix . $parentEvent . '.' . $event
                . EventConstant::EVENT_UPDATE_SEPARATOR;
        }

        return $prefix . $event . EventConstant::EVENT_UPDATE_SEPARATOR;
    }

    public function getEventName(string $event, $value): string
    {
        if (is_array($value)) {
            return '⚙ ' . $event;
        } elseif ($value) {
            return '✅ ' . $event;
        }

        return '❌ ' . $event;
    }

    public function getEndKeyboard(
        string $platform,
        ?string $parentEvent = null
    ): array {
        $back = SettingConstant::SETTING_BACK_TO_SETTINGS_MENU;

        if ($parentEvent) {
            $back = SettingConstant::SETTING_BACK_TO_EVENTS_MENU
                . $platform;
        }

        return [
            $this->telegram->buildInlineKeyBoardButton('🔙 Back', '', $back),
            $this->telegram->buildInlineKeyBoardButton(
                '📚 Menu',
                '',
                SettingConstant::SETTING_BACK_TO_MAIN_MENU
            )
        ];
    }

    public function eventHandle(
        ?string $callback = null,
        ?string $platform = null
    ): void {
        $platform = $this->getPlatformFromCallback($callback, $platform);

        if ($this->sendSettingEventMessage($platform, $callback)) {
            return;
        }

        $event = $this->getEventFromCallback($callback);

        if ($this->handleEventWithActions($event, $platform)) {
            return;
        }

        $this->handleEventUpdate($event, $platform);
    }

    public function getPlatformFromCallback(
        ?string $callback,
        ?string $platform
    ): string {
        if ($platform) {
            return $platform;
        }

        if (str_contains($callback, EventConstant::GITHUB_EVENT_SEPARATOR)) {
            return 'github';
        } elseif (
            str_contains(
                $callback,
                EventConstant::GITLAB_EVENT_SEPARATOR
            )
        ) {
            return 'gitlab';
        }

        return EventConstant::DEFAULT_PLATFORM;
    }

    public function sendSettingEventMessage(
        string $platform,
        ?string $callback = null,
        ?string $view = 'tools.custom_events'
    ): bool {
        if (SettingConstant::SETTING_GITHUB_EVENTS === $callback
            || SettingConstant::SETTING_GITLAB_EVENTS === $callback
            || !$callback
        ) {
            $this->editMessageText(
                view($view, ['platform' => $platform]),
                ['reply_markup' => $this->eventMarkup(null, $platform)]
            );
            return true;
        }

        return false;
    }

    public function getEventFromCallback(?string $callback): string
    {
        return str_replace([
            EventConstant::EVENT_PREFIX,
            EventConstant::GITHUB_EVENT_SEPARATOR,
            EventConstant::GITLAB_EVENT_SEPARATOR
        ], '', $callback);
    }

    public function handleEventWithActions(
        string $event,
        string $platform,
        ?string $view = 'tools.custom_event_actions'
    ): bool {
        if (str_contains($event, EventConstant::EVENT_HAS_ACTION_SEPARATOR)) {
            $event = str_replace(
                EventConstant::EVENT_HAS_ACTION_SEPARATOR,
                '',
                $event
            );
            $this->editMessageText(
                view(
                    $view,
                    compact('event', 'platform')
                ),
                ['reply_markup' => $this->eventMarkup($event, $platform)]
            );
            return true;
        }

        return false;
    }

    public function handleEventUpdate(string $event, string $platform): void
    {
        if (str_contains($event, EventConstant::EVENT_UPDATE_SEPARATOR)) {
            $event = str_replace(
                EventConstant::EVENT_UPDATE_SEPARATOR,
                '',
                $event
            );
            $this->eventUpdateHandle($event, $platform);
        }
    }

    public function eventUpdateHandle(string $event, string $platform): void
    {
        [$event, $action] = explode('.', $event);

        $this->event->setEventConfig($platform);
        $this->event->updateEvent($event, $action);
        $this->eventHandle(
            $action
                ? EventConstant::PLATFORM_EVENT_SEPARATOR[$platform]
                . EventConstant::EVENT_HAS_ACTION_SEPARATOR . $event
                : null,
            $platform
        );
    }
}
