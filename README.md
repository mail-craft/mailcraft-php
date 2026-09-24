# mailcraft/mailcraft-php

Official PHP SDK for the [MailCraft](https://mailcraft.host) email API — transactional email, SMTP relay, marketing campaigns, automations, and contact management for developers and AI agents.

> Using Laravel? Use [`mailcraft/laravel`](https://github.com/mail-craft/mailcraft-laravel) instead — it wraps this package with a `Mail` transport, a facade, and config publishing.

## Install

```bash
composer require mailcraft/mailcraft-php
```

## Usage

```php
use MailCraft\MailCraft;

$mailcraft = new MailCraft(getenv('MAILCRAFT_API_KEY'));

$mailcraft->emails->send(
    from: 'hello@yourdomain.com',
    to: ['person@example.com'],
    subject: 'Welcome!',
    html: '<p>Thanks for signing up.</p>',
);
```

Every resource on the client mirrors a section of the [API reference](https://docs.mailcraft.host/api-reference):

```php
$mailcraft->emails->send(...);
$mailcraft->emails->list();
$mailcraft->emails->get($id);
$mailcraft->emails->validate($email);

$mailcraft->domains->create(...);
$mailcraft->domains->verify($id);

$mailcraft->contacts->upsert(...);
$mailcraft->contacts->addToLists($id, [$listId]);
$mailcraft->contacts->unsubscribe($id);

$mailcraft->campaigns->create(...);
$mailcraft->campaigns->send($id);

$mailcraft->templates->create(...);
$mailcraft->templates->update($id, [...]); // saves a new version

$mailcraft->metrics->get(...);
$mailcraft->metrics->reputation();
```

See [`src/MailCraft.php`](./src/MailCraft.php) for the full list of resources (`domains`, `senders`, `contacts`, `lists`, `segments`, `properties`, `templates`, `templateFolders`, `campaigns`, `webhooks`, `suppressions`, `metrics`).

## Error handling

Every non-2xx response throws a `MailCraft\Exceptions\MailCraftApiException`:

```php
use MailCraft\Exceptions\MailCraftApiException;

try {
    $mailcraft->emails->send(from: 'hello@yourdomain.com', to: ['bad@example.com'], subject: 'Hi');
} catch (MailCraftApiException $e) {
    echo $e->status, $e->type, $e->getMessage();
    // $e->errors holds field-level validation messages, when present
}
```

## Requirements

PHP 8.1+.

## License

MIT
