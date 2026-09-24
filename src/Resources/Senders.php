<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Senders
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /** @return array<string, mixed> */
    public function create(int $domainId, string $email, string $name, ?string $replyTo = null): array
    {
        return $this->client->post('senders', array_filter([
            'domain_id' => $domainId,
            'email' => $email,
            'name' => $name,
            'reply_to' => $replyTo,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('senders');
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("senders/{$id}");
    }

    public function delete(int $id): void
    {
        $this->client->delete("senders/{$id}");
    }
}
