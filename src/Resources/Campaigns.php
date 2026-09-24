<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Campaigns
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * Create a draft campaign targeting exactly one of `$listId` or `$segmentId`.
     *
     * @return array<string, mixed>
     */
    public function create(string $name, string $subject, int $templateId, int $senderId, ?int $listId = null, ?int $segmentId = null): array
    {
        return $this->client->post('campaigns', array_filter([
            'name' => $name,
            'subject' => $subject,
            'template_id' => $templateId,
            'sender_id' => $senderId,
            'list_id' => $listId,
            'segment_id' => $segmentId,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('campaigns');
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("campaigns/{$id}");
    }

    /**
     * Send a draft campaign immediately.
     *
     * @return array<string, mixed>
     */
    public function send(int $id): array
    {
        return $this->client->post("campaigns/{$id}/send");
    }

    public function delete(int $id): void
    {
        $this->client->delete("campaigns/{$id}");
    }
}
