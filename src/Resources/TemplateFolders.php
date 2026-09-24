<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class TemplateFolders
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /** @return array<string, mixed> */
    public function create(string $name): array
    {
        return $this->client->post('template-folders', ['name' => $name]);
    }

    /** @return array<string, mixed> */
    public function list(): array
    {
        return $this->client->get('template-folders');
    }

    public function delete(int $id): void
    {
        $this->client->delete("template-folders/{$id}");
    }
}
