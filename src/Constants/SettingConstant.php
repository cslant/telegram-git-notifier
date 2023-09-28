<?php

namespace LbilTech\TelegramGitNotifier\Constants;

class SettingConstant
{
    public const SETTING_PREFIX = 'stg.';

    public const T_IS_NOTIFIED = 'is_notified';

    public const T_ALL_EVENTS_NOTIFICATION = 'all_events_notify';

    public const V_SETTING_EVENT = 'tools.custom_events';

    public const V_SETTING_EVENT_ACTION = 'tools.custom_event_actions';

    public const SETTING_IS_NOTIFIED = self::SETTING_PREFIX . self::T_IS_NOTIFIED;

    public const SETTING_ALL_EVENTS_NOTIFY = self::SETTING_PREFIX . self::T_ALL_EVENTS_NOTIFICATION;

    public const SETTING_CUSTOM_EVENTS = self::SETTING_PREFIX . 'cus';

    public const SETTING_GITHUB_EVENTS = self::SETTING_CUSTOM_EVENTS . EventConstant::GITHUB_EVENT_SEPARATOR;

    public const SETTING_GITLAB_EVENTS = self::SETTING_CUSTOM_EVENTS . EventConstant::GITLAB_EVENT_SEPARATOR;

    public const BTN_LINE_ITEM_COUNT = 2;

    public const SETTING_BACK = self::SETTING_PREFIX . 'back.';

    public const SETTING_BACK_TO_MAIN_MENU = self::SETTING_BACK . 'menu';

    public const SETTING_BACK_TO_EVENTS_MENU = self::SETTING_BACK . 'settings.custom_events';

    public const SETTING_BACK_TO_SETTINGS_MENU = self::SETTING_BACK . 'settings';
}
