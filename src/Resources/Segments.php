<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Segments
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * @param  array<string, mixed>  $filters  e.g. ['operator' => 'and', 'conditions' => [['field' => 'properties.plan', 'operator' => 'eq', 'value' => 'pro']]]
     * @return array<string, mixed>
     */
    public function create(string $name, array $filters, ?string $description = null): array
    {
        return $this->client->post('segments', array_filter([
            'name' => $name,
            'description' => $description,
            'filters' => $filters,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('segments');
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("segments/{$id}");
    }

    public function delete(int $id): void
    {
        $this->client->delete("segments/{$id}");
    }
}
