<?php

namespace CSlant\TelegramGitNotifier\Constants;

final class EventConstant
{
    public const string EVENT_PREFIX = SettingConstant::SETTING_CUSTOM_EVENTS . '.evt.';

    public const string EVENT_HAS_ACTION_SEPARATOR = 'atc.';

    public const string EVENT_UPDATE_SEPARATOR = '.eus';
}
