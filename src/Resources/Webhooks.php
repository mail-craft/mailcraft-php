<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Webhooks
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * @param  array<int, string>  $events
     * @return array<string, mixed>
     */
    public function create(string $url, array $events, ?string $description = null): array
    {
        return $this->client->post('webhooks', array_filter([
            'url' => $url,
            'events' => $events,
            'description' => $description,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('webhooks');
    }

    public function delete(int $id): void
    {
        $this->client->delete("webhooks/{$id}");
    }
}
