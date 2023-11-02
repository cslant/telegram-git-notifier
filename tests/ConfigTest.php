<?php

use CSlant\TelegramGitNotifier\Bot;

beforeEach(function () {
    $this->bot = new Bot();
});

it('should return true', function () {
    $this->assertTrue(true);
});

it('platform can be set for event with platform parameter', function () {
    $this->bot->setPlatFormForEvent('gitlab');
    expect($this->bot->event->platform)->toBe('gitlab');
});

it('platform can be set for event with null parameter', function () {
    $this->bot->setPlatFormForEvent();
    expect($this->bot->event->platform)->toBe('github');
});

it('platform can be set for event with platform file', function () {
    $this->bot->setPlatFormForEvent('gitlab', 'storage/json/tgn/gitlab-events.json');
    expect($this->bot->event->platform)->toBe('gitlab');
    expect($this->bot->event->getPlatformFile())->toBe('storage/json/tgn/gitlab-events.json');
});

it('can get json config for event - github', function () {
    $this->bot->setPlatFormForEvent();
    expect($this->bot->event->getEventConfig())->toBeArray();
    expect($this->bot->event->getEventConfig())->toHaveKey('issue_comment');
});

it('can get json config for event - gitlab', function () {
    $this->bot->setPlatFormForEvent('gitlab', 'storage/json/tgn/gitlab-events.json');
    expect($this->bot->event->getEventConfig())->toBeArray();
    expect($this->bot->event->getEventConfig())->toHaveKey('tag_push');
});
