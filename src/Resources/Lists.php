<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Lists
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /** @return array<string, mixed> */
    public function create(string $name, ?string $description = null, string $type = 'static', ?int $segmentId = null): array
    {
        return $this->client->post('lists', array_filter([
            'name' => $name,
            'description' => $description,
            'type' => $type,
            'segment_id' => $segmentId,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('lists');
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("lists/{$id}");
    }

    public function delete(int $id): void
    {
        $this->client->delete("lists/{$id}");
    }
}
