<?php

use CSlant\TelegramGitNotifier\DTOs\ChatTarget;

it('can create a ChatTarget with chat id only', function () {
    $target = new ChatTarget('-123456789');

    expect($target->chatId)->toBe('-123456789')
        ->and($target->threadIds)->toBe([])
        ->and($target->hasThreads())->toBeFalse();
});

it('can create a ChatTarget with chat id and thread ids', function () {
    $target = new ChatTarget('-123456789', ['1', '2', '3']);

    expect($target->chatId)->toBe('-123456789')
        ->and($target->threadIds)->toBe(['1', '2', '3'])
        ->and($target->hasThreads())->toBeTrue();
});

it('can parse single chat id from string', function () {
    $target = ChatTarget::fromString('-123456789');

    expect($target->chatId)->toBe('-123456789')
        ->and($target->threadIds)->toBe([]);
});

it('can parse chat id with single thread from string', function () {
    $target = ChatTarget::fromString('-123456789:42');

    expect($target->chatId)->toBe('-123456789')
        ->and($target->threadIds)->toBe(['42']);
});

it('can parse chat id with multiple threads from string', function () {
    $target = ChatTarget::fromString('-123456789:42,100,200');

    expect($target->chatId)->toBe('-123456789')
        ->and($target->threadIds)->toBe(['42', '100', '200']);
});

it('returns empty array when parsing empty string', function () {
    $targets = ChatTarget::parseMultiple('');

    expect($targets)->toBeArray()
        ->toBeEmpty();
});

it('returns empty array when parsing whitespace only string', function () {
    $targets = ChatTarget::parseMultiple('   ');

    expect($targets)->toBeArray()
        ->toBeEmpty();
});

it('can parse multiple chat ids', function () {
    $targets = ChatTarget::parseMultiple('-111111111;-222222222;-333333333');

    expect($targets)->toHaveCount(3)
        ->and($targets[0]->chatId)->toBe('-111111111')
        ->and($targets[1]->chatId)->toBe('-222222222')
        ->and($targets[2]->chatId)->toBe('-333333333');
});

it('can parse multiple chat ids with threads', function () {
    $targets = ChatTarget::parseMultiple('-111111111:1,2;-222222222:3;-333333333');

    expect($targets)->toHaveCount(3)
        ->and($targets[0]->chatId)->toBe('-111111111')
        ->and($targets[0]->threadIds)->toBe(['1', '2'])
        ->and($targets[1]->chatId)->toBe('-222222222')
        ->and($targets[1]->threadIds)->toBe(['3'])
        ->and($targets[2]->chatId)->toBe('-333333333')
        ->and($targets[2]->threadIds)->toBe([]);
});

it('hasThreads returns false for empty thread array', function () {
    $target = new ChatTarget('-123456789', []);

    expect($target->hasThreads())->toBeFalse();
});

it('hasThreads returns true when threads exist', function () {
    $target = new ChatTarget('-123456789', ['1']);

    expect($target->hasThreads())->toBeTrue();
});
