<?php

namespace CSlant\TelegramGitNotifier\Constants;

final class SettingConstant
{
    public const string SETTING_PREFIX = 'stg.';

    public const string T_IS_NOTIFIED = 'is_notified';

    public const string T_ALL_EVENTS_NOTIFICATION = 'all_events_notify';

    public const string SETTING_IS_NOTIFIED = self::SETTING_PREFIX . self::T_IS_NOTIFIED;

    public const string SETTING_ALL_EVENTS_NOTIFY = self::SETTING_PREFIX . self::T_ALL_EVENTS_NOTIFICATION;

    public const string SETTING_CUSTOM_EVENTS = self::SETTING_PREFIX . 'cus';

    public const string SETTING_GITHUB_EVENTS = self::SETTING_CUSTOM_EVENTS . 'gh.';

    public const string SETTING_GITLAB_EVENTS = self::SETTING_CUSTOM_EVENTS . 'gl.';

    public const int BTN_LINE_ITEM_COUNT = 2;

    public const string SETTING_BACK = self::SETTING_PREFIX . 'back.';

    public const string SETTING_BACK_TO_MAIN_MENU = self::SETTING_BACK . 'menu';

    public const string SETTING_BACK_TO_EVENTS_MENU = self::SETTING_BACK . 'settings.custom_events';

    public const string SETTING_BACK_TO_SETTINGS_MENU = self::SETTING_BACK . 'settings';
}
