<?php

use CSlant\TelegramGitNotifier\Webhook;

it('can be instantiated', function () {
    $webhook = new Webhook();

    expect($webhook)->toBeInstanceOf(Webhook::class);
});

it('can set token', function () {
    $webhook = new Webhook();
    $webhook->setToken('test-token');

    expect($webhook)->toBeInstanceOf(Webhook::class);
});

it('can set url', function () {
    $webhook = new Webhook();
    $webhook->setUrl('https://example.com/webhook');

    expect($webhook)->toBeInstanceOf(Webhook::class);
});

it('implements WebhookInterface', function () {
    $webhook = new Webhook();

    expect($webhook)->toBeInstanceOf(\CSlant\TelegramGitNotifier\Interfaces\WebhookInterface::class);
});

it('has setWebhook method', function () {
    $webhook = new Webhook();

    expect(method_exists($webhook, 'setWebhook'))->toBeTrue();
});

it('has deleteWebHook method', function () {
    $webhook = new Webhook();

    expect(method_exists($webhook, 'deleteWebHook'))->toBeTrue();
});

it('has getWebHookInfo method', function () {
    $webhook = new Webhook();

    expect(method_exists($webhook, 'getWebHookInfo'))->toBeTrue();
});

it('has getUpdates method', function () {
    $webhook = new Webhook();

    expect(method_exists($webhook, 'getUpdates'))->toBeTrue();
});
