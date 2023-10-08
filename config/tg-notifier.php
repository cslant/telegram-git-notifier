<?php

return [
    'app' => [
        'name'     => $_ENV['TGN_APP_NAME'] ?? 'Telegram Git Notifier',
        'url'      => $_ENV['TGN_APP_URL'] ?? 'http://localhost:3000',
        'timezone' => $_ENV['TIMEZONE'] ?? 'Asia/Ho_Chi_Minh',
    ],

    'telegram-bot' => [
        'token'           => $_ENV['TELEGRAM_BOT_TOKEN'] ?? '',
        'chat_id'         => $_ENV['TELEGRAM_BOT_CHAT_ID'] ?? '',
        'notify_chat_ids' => explode(
            ',',
            $_ENV['TELEGRAM_NOTIFY_CHAT_IDS'] ?? ''
        ),
    ],

    'author' => [
        'contact'     => $_ENV['TGN_AUTHOR_CONTACT'] ?? 'https://t.me/tannp27',
        'source_code' => $_ENV['TGN_AUTHOR_SOURCE_CODE'] ??
            'https://github.com/lbiltech/telegram-git-notifier',
    ],

    'view' => [
        'path'  => $_ENV['TGN_VIEW_PATH'] ??
            'resources/views/telegram-git-notifier',
        'event' => [
            'default' => $_ENV['TGN_VIEW_EVENT_DEFAULT'] ?? 'default',
        ],

        'globals' => [
            'access_denied' => $_ENV['TGN_VIEW_GLOBALS_ACCESS_DENIED'] ??
                'globals.access_denied',
        ],

        'tools' => [
            'settings'            => $_ENV['TGN_VIEW_TOOL_SETTING'] ??
                'tools.settings',
            'custom_event_action' => $_ENV['TGN_VIEW_TOOL_CUSTOM_EVENT_ACTION']
                ?? 'tools.custom_event_action',
            'custom_event'        => $_ENV['TGN_VIEW_TOOL_CUSTOM_EVENT'] ??
                'tools.custom_event',
            'set_menu_command'    => $_ENV['TGN_VIEW_TOOL_SET_MENU_COMMAND'] ??
                'tools.set_menu_command',
        ],
    ]
];
