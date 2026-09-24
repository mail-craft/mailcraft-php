<?php

declare(strict_types=1);

namespace MailCraft;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use MailCraft\Exceptions\MailCraftApiException;

/**
 * Thin Guzzle-based HTTP client shared by every resource.
 *
 * @internal Consumers only ever interact with the typed resource classes
 *           exposed on {@see MailCraft}.
 */
final class Client
{
    private readonly ClientInterface $http;

    /**
     * @param  ClientInterface|null  $httpClient  inject a pre-configured Guzzle client (e.g. with a MockHandler) for testing; production use never needs this.
     */
    public function __construct(string $apiKey, string $baseUrl = 'https://api.mailcraft.host/v1', float $timeoutSeconds = 30.0, ?ClientInterface $httpClient = null)
    {
        if ($apiKey === '') {
            throw new \InvalidArgumentException('MailCraft: an API key is required. Find yours under Settings > API Keys.');
        }

        $this->http = $httpClient ?? new GuzzleClient([
            'base_uri' => rtrim($baseUrl, '/').'/',
            'timeout' => $timeoutSeconds,
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
                'Accept' => 'application/json',
                'User-Agent' => 'mailcraft-php/0.1.0',
            ],
        ]);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, ['query' => $query]);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function post(string $path, array $body = []): array
    {
        return $this->request('POST', $path, ['json' => $body]);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function patch(string $path, array $body = []): array
    {
        return $this->request('PATCH', $path, ['json' => $body]);
    }

    public function delete(string $path): void
    {
        $this->request('DELETE', $path);
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    private function request(string $method, string $path, array $options = []): array
    {
        try {
            $response = $this->http->request($method, ltrim($path, '/'), $options);
        } catch (RequestException $exception) {
            $errorResponse = $exception->getResponse();

            if ($errorResponse !== null) {
                throw MailCraftApiException::fromResponse($errorResponse, $exception);
            }

            throw $exception;
        } catch (GuzzleException $exception) {
            throw $exception;
        }

        $status = $response->getStatusCode();
        $rawBody = (string) $response->getBody();

        if ($status === 204 || $rawBody === '') {
            return [];
        }

        /** @var array<string, mixed> $decoded */
        $decoded = json_decode($rawBody, true, flags: JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
