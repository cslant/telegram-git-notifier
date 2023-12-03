<?php

return [
    'telegram-git-notifier' => [
        'app' => [
            'name'     => $_ENV['TGN_APP_NAME'] ?? 'Telegram Git Notifier',

            // Required for the bot to work properly
            'url'      => $_ENV['TGN_APP_URL'] ?? 'http://localhost:3000',

            'timezone' => $_ENV['TIMEZONE'] ?? 'Asia/Ho_Chi_Minh',
        ],

        'bot' => [
            'token'           => $_ENV['TELEGRAM_BOT_TOKEN'] ?? '',
            'chat_id'         => $_ENV['TELEGRAM_BOT_CHAT_ID'] ?? '',

            /**
             * Set the chat IDs that will receive notifications
             * You can add the owner bot ID, group ID, ...
             * -------------------------------------------------------
             * Note:
             * Please use semicolon ";" to separate chat ids
             * And use a colon ":" to separate chat ID and thread ID
             * And use comma "," if you want to add multiple thread ids
             * -------------------------------------------------------
             * The environment variable is expected to be in the format:
             * "chat_id1;chat_id2:thread_id2;chat_id3:thread_id3_1,thread_id3_2;..."
            */
            'notify_chat_ids' => $_ENV['TELEGRAM_NOTIFY_CHAT_IDS'] ?? '',
        ],

        'author' => [
            'discussion'  => $_ENV['TGN_AUTHOR_DISCUSSION'] ??
                'https://github.com/cslant/telegram-git-notifier/discussions',
            'source_code' => $_ENV['TGN_AUTHOR_SOURCE_CODE'] ??
                'https://github.com/cslant/telegram-git-notifier',
        ],

        /** Set the path to the data file */
        'data_file' => [
            'setting'  => $_ENV['TGN_PATH_SETTING'] ??
                'storage/json/tgn/tgn-settings.json',

            'platform' => [
                'gitlab' => $_ENV['TGN_PATH_PLATFORM_GITLAB'] ??
                    'storage/json/tgn/gitlab-events.json',
                'github' => $_ENV['TGN_PATH_PLATFORM_GITHUB'] ??
                    'storage/json/tgn/github-events.json',
            ],
        ],

        /** Set the path to the view file */
        'view' => [
            'namespace' => $_ENV['TGN_VIEW_NAMESPACE'] ?? 'tg-notifier',

            'path' => $_ENV['TGN_VIEW_PATH'] ??
                'resources/views',

            'ignore-message' => $_ENV['IGNORE_MESSAGE'] ?? 'ignore-message',

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
                'set_menu_cmd'        => $_ENV['TGN_VIEW_TOOL_SET_MENU_COMMAND']
                    ?? 'tools.set_menu_cmd',
            ],
        ],
    ],
];
