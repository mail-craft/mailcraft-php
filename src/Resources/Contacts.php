<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Contacts
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * Create a contact, or update it if one already exists for this email.
     *
     * @param  array<string, mixed>  $properties
     * @return array<string, mixed>
     */
    public function upsert(
        string $email,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $status = null,
        array $properties = [],
    ): array {
        return $this->client->post('contacts', array_filter([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'status' => $status,
            'properties' => $properties ?: null,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(int $limit = 25): array
    {
        return $this->client->get('contacts', ['limit' => $limit]);
    }

    /** @return array<string, mixed> */
    public function get(string $id): array
    {
        return $this->client->get("contacts/{$id}");
    }

    public function delete(string $id): void
    {
        $this->client->delete("contacts/{$id}");
    }

    /**
     * Unsubscribe a contact — also suppresses them from future sends.
     *
     * @return array<string, mixed>
     */
    public function unsubscribe(string $id): array
    {
        return $this->client->post("contacts/{$id}/unsubscribe");
    }

    /** @param  array<int, int>  $listIds */
    public function addToLists(string $id, array $listIds): void
    {
        $this->client->post("contacts/{$id}/lists", ['list_ids' => $listIds]);
    }

    /** @return array<string, mixed> */
    public function lists(string $id): array
    {
        return $this->client->get("contacts/{$id}/lists");
    }

    public function removeFromList(string $id, int $listId): void
    {
        $this->client->delete("contacts/{$id}/lists/{$listId}");
    }
}
