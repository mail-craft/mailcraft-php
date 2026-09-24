<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Emails
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * Send a single transactional email. Returns immediately with
     * `status: "queued"` — the send happens asynchronously.
     *
     * @param  array<int, string>  $to
     * @param  array<int, string>  $cc
     * @param  array<int, string>  $bcc
     * @param  array<string, string>  $headers
     * @param  array<string, string>  $tags
     * @return array<string, mixed>
     */
    public function send(
        string $from,
        array $to,
        string $subject,
        ?string $html = null,
        ?string $text = null,
        array $cc = [],
        array $bcc = [],
        ?string $replyTo = null,
        array $headers = [],
        array $tags = [],
    ): array {
        return $this->client->post('emails', array_filter([
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'html' => $html,
            'text' => $text,
            'cc' => $cc ?: null,
            'bcc' => $bcc ?: null,
            'reply_to' => $replyTo,
            'headers' => $headers ?: null,
            'tags' => $tags ?: null,
        ], fn (mixed $value) => $value !== null));
    }

    /** @return array<string, mixed> */
    public function list(int $limit = 25): array
    {
        return $this->client->get('emails', ['limit' => $limit]);
    }

    /** @return array<string, mixed> */
    public function get(string $id): array
    {
        return $this->client->get("emails/{$id}");
    }

    /**
     * Validate an email address's format, MX record, and disposable-domain status.
     *
     * @return array<string, mixed>
     */
    public function validate(string $email): array
    {
        return $this->client->get('emails/validate', ['email' => $email]);
    }
}
