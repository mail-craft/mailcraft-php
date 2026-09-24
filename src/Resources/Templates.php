<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Templates
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /** @return array<string, mixed> */
    public function create(string $name, string $subject, ?string $htmlBody = null, ?string $textBody = null, ?int $templateFolderId = null): array
    {
        return $this->client->post('templates', array_filter([
            'name' => $name,
            'subject' => $subject,
            'html_body' => $htmlBody,
            'text_body' => $textBody,
            'template_folder_id' => $templateFolderId,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('templates');
    }

    /** @return array<string, mixed> */
    public function get(int $id): array
    {
        return $this->client->get("templates/{$id}");
    }

    /**
     * Updating a template saves a new version — prior versions stay intact
     * for campaigns/emails that already pinned them.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function update(int $id, array $attributes): array
    {
        return $this->client->patch("templates/{$id}", $attributes);
    }

    public function delete(int $id): void
    {
        $this->client->delete("templates/{$id}");
    }
}
