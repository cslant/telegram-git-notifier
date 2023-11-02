<?php

use LbilTech\TelegramGitNotifier\Notifier;

beforeEach(function () {
    $this->nofitier = new Notifier();
});

it('validates that the event files exist', function () {
    $this->nofitier->setPlatFormForEvent('gitlab', 'storage/json/tgn/gitlab-events.json');
    expect($this->nofitier->event->getEventConfig())->toBeArray();
    expect($this->nofitier->event->getEventConfig())->toHaveKey('tag_push');

    $this->nofitier->setPlatFormForEvent('github', 'storage/json/tgn/github-events.json');
    expect($this->nofitier->event->getEventConfig())->toBeArray();
    expect($this->nofitier->event->getEventConfig())->toHaveKey('issue_comment');
});

it('can parse notification chat IDs', function () {
    $chatThreadMapping = $this->nofitier->parseNotifyChatIds('-1201937489183;-1008168942527:46,2');
    expect($chatThreadMapping)->toBeArray();
    expect($chatThreadMapping)->toHaveKey('-1008168942527');
    expect($chatThreadMapping['-1008168942527'])->toBeArray();
    expect($chatThreadMapping['-1008168942527'])->toBe([0 => '46', 1 => '2']);
});
