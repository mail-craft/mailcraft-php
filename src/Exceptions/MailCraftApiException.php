<?php

declare(strict_types=1);

namespace MailCraft\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * Thrown for any non-2xx response from the MailCraft API.
 *
 * Mirrors the two error shapes the API actually returns: a business-rule
 * failure (`{"error": {"type", "message"}}`, e.g. plan limits or a
 * suppressed recipient) or a Laravel validation failure
 * (`{"message", "errors": {field: [messages]}}`, HTTP 422).
 */
class MailCraftApiException extends Exception
{
    public readonly int $status;

    public readonly ?string $type;

    /** @var array<string, array<int, string>>|null */
    public readonly ?array $errors;

    /**
     * @param  array<string, array<int, string>>|null  $errors
     */
    public function __construct(int $status, string $message, ?string $type = null, ?array $errors = null, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->status = $status;
        $this->type = $type;
        $this->errors = $errors;
    }

    public static function fromResponse(ResponseInterface $response, ?Throwable $previous = null): self
    {
        $status = $response->getStatusCode();
        $body = json_decode((string) $response->getBody(), true);

        if (is_array($body) && isset($body['error']) && is_array($body['error'])) {
            return new self(
                $status,
                $body['error']['message'] ?? $response->getReasonPhrase(),
                $body['error']['type'] ?? null,
                previous: $previous,
            );
        }

        if (is_array($body) && isset($body['message'])) {
            return new self(
                $status,
                (string) $body['message'],
                errors: $body['errors'] ?? null,
                previous: $previous,
            );
        }

        return new self($status, $response->getReasonPhrase(), previous: $previous);
    }
}
