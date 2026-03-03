<?php

use CSlant\TelegramGitNotifier\Exceptions\WebhookException;
use CSlant\TelegramGitNotifier\Webhook;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->mockClient = Mockery::mock(Client::class);
    $this->webhook = new Webhook($this->mockClient);
    $this->webhook->setToken('test-token');
    $this->webhook->setUrl('https://example.com/webhook');
});

it('can set token', function () {
    $webhook = new Webhook();
    $webhook->setToken('new-token');

    expect($webhook)->toBeInstanceOf(Webhook::class);
});

it('can set url', function () {
    $webhook = new Webhook();
    $webhook->setUrl('https://new-url.com/webhook');

    expect($webhook)->toBeInstanceOf(Webhook::class);
});

it('can set webhook successfully', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->with('GET', 'https://api.telegram.org/bottest-token/setWebhook?url=https://example.com/webhook', Mockery::any())
        ->andReturn(new Response(200, [], '{"ok":true,"result":true,"description":"Webhook was set"}'));

    $result = $this->webhook->setWebhook();

    expect($result)->toContain('Webhook was set');
});

it('can delete webhook successfully', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->with('GET', 'https://api.telegram.org/bottest-token/deleteWebhook', Mockery::any())
        ->andReturn(new Response(200, [], '{"ok":true,"result":true,"description":"Webhook was deleted"}'));

    $result = $this->webhook->deleteWebHook();

    expect($result)->toContain('Webhook was deleted');
});

it('can get webhook info successfully', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->with('GET', 'https://api.telegram.org/bottest-token/getWebhookInfo', Mockery::any())
        ->andReturn(new Response(200, [], '{"ok":true,"result":{"url":"https://example.com/webhook"}}'));

    $result = $this->webhook->getWebHookInfo();

    expect($result)->toContain('https://example.com/webhook');
});

it('can get updates successfully', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->with('GET', 'https://api.telegram.org/bottest-token/getUpdates', Mockery::any())
        ->andReturn(new Response(200, [], '{"ok":true,"result":[]}'));

    $result = $this->webhook->getUpdates();

    expect($result)->json()->toBe(['ok' => true, 'result' => []]);
});

it('throws exception on failed webhook set', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->andThrow(new RequestException('Error', new Request('GET', 'test')));

    $this->webhook->setWebhook();
})->throws(WebhookException::class);

it('throws exception on failed webhook delete', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->andThrow(new RequestException('Error', new Request('GET', 'test')));

    $this->webhook->deleteWebHook();
})->throws(WebhookException::class);

it('throws exception on failed get webhook info', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->andThrow(new RequestException('Error', new Request('GET', 'test')));

    $this->webhook->getWebHookInfo();
})->throws(WebhookException::class);

it('throws exception on failed get updates', function () {
    $this->mockClient
        ->shouldReceive('request')
        ->once()
        ->andThrow(new RequestException('Error', new Request('GET', 'test')));

    $this->webhook->getUpdates();
})->throws(WebhookException::class);

it('retries on rate limit (429) and succeeds', function () {
    $callCount = 0;

    $this->mockClient
        ->shouldReceive('request')
        ->times(2)
        ->andReturnUsing(function () use (&$callCount) {
            $callCount++;
            if ($callCount === 1) {
                throw new RequestException('429 Too Many Requests', new Request('GET', 'test'));
            }

            return new Response(200, [], '{"ok":true,"result":true}');
        });

    $result = $this->webhook->setWebhook();

    expect($callCount)->toBe(2)
        ->and($result)->toBe('{"ok":true,"result":true}');
});

it('uses custom guzzle client when provided', function () {
    $customClient = Mockery::mock(Client::class);
    $customClient
        ->shouldReceive('request')
        ->once()
        ->andReturn(new Response(200, [], '{"ok":true}'));

    $webhook = new Webhook($customClient);
    $webhook->setToken('test-token');
    $webhook->setUrl('https://example.com/webhook');

    $result = $webhook->setWebhook();

    expect($result)->toBe('{"ok":true}');
});
