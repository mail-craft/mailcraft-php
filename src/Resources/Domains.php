<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Domains
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /** @return array<string, mixed> */
    public function create(string $name, string $region = 'us-east-1'): array
    {
        return $this->client->post('domains', ['name' => $name, 'region' => $region]);
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('domains');
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("domains/{$id}");
    }

    /**
     * Re-check DKIM verification status against SES.
     *
     * @return array<string, mixed>
     */
    public function verify(int $id): array
    {
        return $this->client->post("domains/{$id}/verify");
    }

    public function delete(int $id): void
    {
        $this->client->delete("domains/{$id}");
    }
}
