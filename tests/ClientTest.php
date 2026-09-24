<?php

declare(strict_types=1);

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MailCraft\Client;
use MailCraft\Exceptions\MailCraftApiException;

function mockedClient(array $responses): Client
{
    $handlerStack = HandlerStack::create(new MockHandler($responses));
    $guzzle = new GuzzleClient(['base_uri' => 'https://api.mailcraft.host/v1/', 'handler' => $handlerStack]);

    return new Client('sk_test_key', httpClient: $guzzle);
}

test('a missing api key throws immediately', function () {
    expect(fn () => new Client(''))->toThrow(InvalidArgumentException::class);
});

test('get decodes a successful json response', function () {
    $client = mockedClient([
        new Response(200, ['Content-Type' => 'application/json'], json_encode(['data' => ['id' => 'em_123']])),
    ]);

    $result = $client->get('emails/em_123');

    expect($result)->toBe(['data' => ['id' => 'em_123']]);
});

test('delete returns nothing for a 204 response', function () {
    $client = mockedClient([new Response(204)]);

    expect($client->delete('domains/1'))->toBeNull();
});

test('a business-rule error response is thrown as a MailCraftApiException', function () {
    $client = mockedClient([
        new Response(402, ['Content-Type' => 'application/json'], json_encode([
            'error' => ['type' => 'daily_send_cap_exceeded', 'message' => 'You have reached your plan\'s daily sending limit.'],
        ])),
    ]);

    try {
        $client->post('emails', ['from' => 'a@b.com']);
        $this->fail('Expected MailCraftApiException to be thrown.');
    } catch (MailCraftApiException $e) {
        expect($e->status)->toBe(402);
        expect($e->type)->toBe('daily_send_cap_exceeded');
        expect($e->getMessage())->toContain('daily sending limit');
    }
});

test('a validation error response exposes field-level errors', function () {
    $client = mockedClient([
        new Response(422, ['Content-Type' => 'application/json'], json_encode([
            'message' => 'The given data was invalid.',
            'errors' => ['to' => ['The to field is required.']],
        ])),
    ]);

    try {
        $client->post('emails', []);
        $this->fail('Expected MailCraftApiException to be thrown.');
    } catch (MailCraftApiException $e) {
        expect($e->status)->toBe(422);
        expect($e->errors)->toBe(['to' => ['The to field is required.']]);
    }
});
