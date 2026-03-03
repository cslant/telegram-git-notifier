<?php

use CSlant\TelegramGitNotifier\Bot;
use CSlant\TelegramGitNotifier\Enums\Platform;
use CSlant\TelegramGitNotifier\Models\Event;
use CSlant\TelegramGitNotifier\Models\Setting;

it('can be instantiated with default parameters', function () {
    $bot = new Bot();

    expect($bot)->toBeInstanceOf(Bot::class)
        ->and($bot->event)->toBeInstanceOf(Event::class)
        ->and($bot->setting)->toBeInstanceOf(Setting::class);
});

it('can be instantiated with custom event', function () {
    $event = new Event();
    $bot = new Bot(event: $event);

    expect($bot->event)->toBe($event);
});

it('can be instantiated with custom platform', function () {
    $bot = new Bot(platform: Platform::GITHUB);

    expect($bot->event->platform)->toBe(Platform::GITHUB);
});

it('can be instantiated with custom setting', function () {
    $setting = new Setting();
    $bot = new Bot(setting: $setting);

    expect($bot->setting)->toBe($setting);
});

it('sets default platform when not specified', function () {
    $bot = new Bot();

    expect($bot->event->platform)->toBe(Platform::DEFAULT);
});

it('validates platform file on construction', function () {
    // This should not throw if the platform file exists
    $bot = new Bot(platform: Platform::GITHUB);

    expect($bot->event->getEventConfig())->toBeArray();
});

it('validates setting file on construction', function () {
    $bot = new Bot();

    expect($bot->setting->getSettingFile())->not->toBeEmpty();
});
