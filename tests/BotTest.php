<?php

use CSlant\TelegramGitNotifier\Bot;
use CSlant\TelegramGitNotifier\Models\Event;
use CSlant\TelegramGitNotifier\Models\Setting;

it('can be instantiated with default parameters', function () {
    $bot = new Bot(
        platformFile: __DIR__ . '/../config/jsons/github-events.json',
        settingFile: __DIR__ . '/../config/jsons/tgn-settings.json'
    );

    expect($bot)->toBeInstanceOf(Bot::class)
        ->and($bot->event)->toBeInstanceOf(Event::class)
        ->and($bot->setting)->toBeInstanceOf(Setting::class);
});

it('can be instantiated with custom event', function () {
    $event = new Event();
    $bot = new Bot(
        event: $event,
        platformFile: __DIR__ . '/../config/jsons/github-events.json',
        settingFile: __DIR__ . '/../config/jsons/tgn-settings.json'
    );

    expect($bot->event)->toBe($event);
});

it('can be instantiated with custom platform', function () {
    $bot = new Bot(
        platform: 'github',
        platformFile: __DIR__ . '/../config/jsons/github-events.json',
        settingFile: __DIR__ . '/../config/jsons/tgn-settings.json'
    );

    expect($bot->event->platform)->toBe('github');
});

it('can be instantiated with custom setting', function () {
    $setting = new Setting();
    $bot = new Bot(
        setting: $setting,
        platformFile: __DIR__ . '/../config/jsons/github-events.json',
        settingFile: __DIR__ . '/../config/jsons/tgn-settings.json'
    );

    expect($bot->setting)->toBe($setting);
});

it('sets default platform when not specified', function () {
    $bot = new Bot(
        platformFile: __DIR__ . '/../config/jsons/github-events.json',
        settingFile: __DIR__ . '/../config/jsons/tgn-settings.json'
    );

    expect($bot->event->platform)->toBe('github');
});

it('validates platform file on construction', function () {
    // This should not throw if the platform file exists
    $bot = new Bot(
        platform: 'github',
        platformFile: __DIR__ . '/../config/jsons/github-events.json',
        settingFile: __DIR__ . '/../config/jsons/tgn-settings.json'
    );

    expect($bot->event->getEventConfig())->toBeArray();
});

it('validates setting file on construction', function () {
    $bot = new Bot(
        platformFile: __DIR__ . '/../config/jsons/github-events.json',
        settingFile: __DIR__ . '/../config/jsons/tgn-settings.json'
    );

    expect($bot->setting->getSettingFile())->not->toBeEmpty();
});
