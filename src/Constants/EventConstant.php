<?php

namespace CSlant\TelegramGitNotifier\Constants;

final class EventConstant
{
    public const DEFAULT_PLATFORM = 'github';

    public const WEBHOOK_EVENT_HEADER = [
        'github' => 'HTTP_X_GITHUB_EVENT',
        'gitlab' => 'HTTP_X_GITLAB_EVENT',
    ];

    public const EVENT_PREFIX = SettingConstant::SETTING_CUSTOM_EVENTS . '.evt.';

    public const GITHUB_EVENT_SEPARATOR = 'gh.';

    public const GITLAB_EVENT_SEPARATOR = 'gl.';

    public const EVENT_HAS_ACTION_SEPARATOR = 'atc.';

    public const EVENT_UPDATE_SEPARATOR = '.eus';

    public const PLATFORM_EVENT_SEPARATOR = [
        'github' => self::GITHUB_EVENT_SEPARATOR,
        'gitlab' => self::GITLAB_EVENT_SEPARATOR,
    ];
}
