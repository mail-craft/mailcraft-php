<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Properties
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /** @return array<string, mixed> */
    public function create(string $key, string $label, string $type, mixed $defaultValue = null): array
    {
        return $this->client->post('properties', array_filter([
            'key' => $key,
            'label' => $label,
            'type' => $type,
            'default_value' => $defaultValue,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('properties');
    }

    public function delete(int $id): void
    {
        $this->client->delete("properties/{$id}");
    }
}
