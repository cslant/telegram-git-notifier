<?php

namespace CSlant\TelegramGitNotifier\Constants;

final class NotificationConstant
{
    /** @var string Separation between chat id pairs */
    public const CHAT_ID_PAIRS_SEPARATOR = ';';

    /** @var string Separation between chat id and thread id */
    public const CHAT_THREAD_ID_SEPARATOR = ':';

    /** @var string Separation between thread ids */
    public const THREAD_ID_SEPARATOR = ',';
}
