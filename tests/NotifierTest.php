<?php

use CSlant\TelegramGitNotifier\Notifier;

beforeEach(function () {
    $this->notifier = new Notifier();
});

it('validates that the event files exist', function () {
    $this->notifier->setPlatFormForEvent('gitlab', 'storage/json/tgn/gitlab-events.json');
    expect($this->notifier->event->getEventConfig())->toBeArray()
        ->and($this->notifier->event->getEventConfig())->toHaveKey('tag_push');

    $this->notifier->setPlatFormForEvent('github', 'storage/json/tgn/github-events.json');
    expect($this->notifier->event->getEventConfig())->toBeArray()
        ->and($this->notifier->event->getEventConfig())
        ->toHaveKey('issue_comment');
});

it('can parse notification chat IDs', function () {
    $chatThreadMapping = $this->notifier->parseNotifyChatIds('-1201937489183;-1008168942527:46,2');
    expect($chatThreadMapping)->toBeArray()
        ->and($chatThreadMapping)->toHaveKey('-1008168942527')
        ->and($chatThreadMapping['-1008168942527'])->toBeArray()
        ->and($chatThreadMapping['-1008168942527'])->toBe([
            0 => '46', 1 => '2',
        ]);
});
