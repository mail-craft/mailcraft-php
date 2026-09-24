<?php

declare(strict_types=1);

namespace MailCraft;

use MailCraft\Resources\Campaigns;
use MailCraft\Resources\Contacts;
use MailCraft\Resources\Domains;
use MailCraft\Resources\Emails;
use MailCraft\Resources\Lists;
use MailCraft\Resources\Metrics;
use MailCraft\Resources\Properties;
use MailCraft\Resources\Segments;
use MailCraft\Resources\Senders;
use MailCraft\Resources\Suppressions;
use MailCraft\Resources\TemplateFolders;
use MailCraft\Resources\Templates;
use MailCraft\Resources\Webhooks;

/**
 * The MailCraft API client.
 *
 * ```php
 * $mailcraft = new MailCraft(getenv('MAILCRAFT_API_KEY'));
 *
 * $mailcraft->emails->send(
 *     from: 'hello@yourdomain.com',
 *     to: ['person@example.com'],
 *     subject: 'Welcome!',
 *     html: '<p>Thanks for signing up.</p>',
 * );
 * ```
 */
final class MailCraft
{
    public readonly Emails $emails;

    public readonly Domains $domains;

    public readonly Senders $senders;

    public readonly Contacts $contacts;

    public readonly Lists $lists;

    public readonly Segments $segments;

    public readonly Properties $properties;

    public readonly Templates $templates;

    public readonly TemplateFolders $templateFolders;

    public readonly Campaigns $campaigns;

    public readonly Webhooks $webhooks;

    public readonly Suppressions $suppressions;

    public readonly Metrics $metrics;

    /**
     * @param  Client|null  $client  inject a pre-configured Client (e.g. wrapping a mocked Guzzle handler) for testing; production use never needs this.
     */
    public function __construct(string $apiKey, string $baseUrl = 'https://api.mailcraft.host/v1', ?Client $client = null)
    {
        $client ??= new Client($apiKey, $baseUrl);

        $this->emails = new Emails($client);
        $this->domains = new Domains($client);
        $this->senders = new Senders($client);
        $this->contacts = new Contacts($client);
        $this->lists = new Lists($client);
        $this->segments = new Segments($client);
        $this->properties = new Properties($client);
        $this->templates = new Templates($client);
        $this->templateFolders = new TemplateFolders($client);
        $this->campaigns = new Campaigns($client);
        $this->webhooks = new Webhooks($client);
        $this->suppressions = new Suppressions($client);
        $this->metrics = new Metrics($client);
    }

    /**
     * Method-call access to each resource (`$mailcraft->emails()`), in
     * addition to the normal property access (`$mailcraft->emails`).
     *
     * Property access is the idiomatic way to use this SDK directly; this
     * exists so `mailcraft/laravel`'s Facade — which can only forward
     * static *method* calls, not property reads — can expose the same
     * resources as `MailCraft::emails()->send(...)`.
     *
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        throw new \BadMethodCallException(sprintf('Call to undefined method %s::%s()', self::class, $name));
    }
}
