<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Suppressions
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /** @return array<string, mixed> */
    public function add(string $email, ?string $reason = null): array
    {
        return $this->client->post('suppressions', array_filter([
            'email' => $email,
            'reason' => $reason,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(int $limit = 50): array
    {
        return $this->client->get('suppressions', ['limit' => $limit]);
    }

    public function delete(int $id): void
    {
        $this->client->delete("suppressions/{$id}");
    }
}
