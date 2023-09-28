<?php

return [
    'app' => [
        'name'     => $_ENV['TGN_APP_NAME'] ?? 'Telegram Git Notifier',
        'url'      => $_ENV['TGN_APP_URL'] ?? 'http://localhost:3000',
        'image'    => $_ENV['TGN_APP_IMAGE'] ?? 'public/images/github.jpeg',
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
        ]
    ]
];