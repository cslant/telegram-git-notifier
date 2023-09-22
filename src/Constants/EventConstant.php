<?php

namespace LbilTech\TelegramGitNotifier\Constants;

class EventConstant
{
    public const DEFAULT_PLATFORM = 'github';

    public const EVENT_PREFIX = SettingConstant::SETTING_CUSTOM_EVENTS . '.evt.';

    public const GITHUB_EVENT_SEPARATOR = 'gh.';

    public const GITLAB_EVENT_SEPARATOR = 'gl.';

    public const EVENT_HAS_ACTION_SEPARATOR = 'atc.';

    public const EVENT_UPDATE_SEPARATOR = '.eus';
}
