<?php

use CSlant\TelegramGitNotifier\Bot;
use CSlant\TelegramGitNotifier\Objects\Validator;

beforeEach(function () {
    $this->bot = new Bot(
        platformFile: __DIR__.'/../config/jsons/github-events.json',
        settingFile: __DIR__.'/../config/jsons/tgn-settings.json'
    );
    $this->validator = new Validator($this->bot->setting, $this->bot->event);
});

it('can validate the event that has no action to send a notification - GitHub', function () {
    $result = $this->validator->isAccessEvent('github', 'push', (object)[]);
    expect($result)->toBeTrue();
});

it('can validate the event that has an action to send a notification - GitHub', function () {
    $result = $this->validator->isAccessEvent('github', 'pull_request', (object)[
        'action' => 'opened',
    ]);
    expect($result)->toBeTrue();
});

it('can\'t validate the event that has an action but no payload to send a notification - GitHub', function () {
    $result = $this->validator->isAccessEvent('github', 'pull_request', (object)[]);
    expect($result)->toBeFalse();
});

it('can validate the event that has no action to send a notification - gitlab', function () {
    $this->bot->setPlatFormForEvent('gitlab', __DIR__.'/../config/jsons/gitlab-events.json');
    $result = $this->validator->isAccessEvent('gitlab', 'push', (object)[]);
    expect($result)->toBeTrue();
});

it('can validate the event that has an action to send a notification - gitlab', function () {
    $this->bot->setPlatFormForEvent('gitlab', __DIR__.'/../config/jsons/gitlab-events.json');
    $result = $this->validator->isAccessEvent('gitlab', 'merge_request', (object)[
        'action' => 'open',
    ]);
    expect($result)->toBeTrue();
});

it('can\'t validate the event that has an action but no payload to send a notification - gitlab', function () {
    $this->bot->setPlatFormForEvent('gitlab', __DIR__.'/../config/jsons/gitlab-events.json');
    $result = $this->validator->isAccessEvent('gitlab', 'merge_request', (object)[]);
    expect($result)->toBeFalse();
});
