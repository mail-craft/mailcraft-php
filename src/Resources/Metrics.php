<?php

declare(strict_types=1);

namespace MailCraft\Resources;

use MailCraft\Client;

final class Metrics
{
    public function __construct(private readonly Client $client)
    {
        //
    }

    /**
     * Get daily email metrics over a date range (defaults to the last 30 days).
     *
     * @return array<string, mixed>
     */
    public function get(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->client->get('metrics', array_filter([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ], fn (mixed $value) => $value !== null));
    }

    /**
     * Get the rolling 7-day sending reputation (bounce/complaint rate).
     *
     * @return array<string, mixed>
     */
    public function reputation(): array
    {
        return $this->client->get('reputation');
    }
}
