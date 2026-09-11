![Grimlock Logo](grimlock.png)

# Grimlock - Libraries and Utilities for PHP

---

## What is Grimlock?
It is a set of libraries and utilities for PHP.

## Capabilities

* REST Client
* PDF Generation from HTML
* Firebase Push Notifications
* Whatsapp Notification
* Email Sending
* Logging

## Requirements

* PHP 8.4 or higher
* Composer 2.9.5 or higher

## Dependencies

* Guzzle
* DomPDF
* PHPMailer
* PHPUnit
* Monolog
* Google Auth

## Recommendations

Visit the wiki for more information:
https://github.com/stoneneo/grimlock/wiki

## Installation

Installation is super easy with [Composer](https://getcomposer.org/):

```bash
composer require gorilla-soft/grimlock
```

```php
// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md
require 'vendor/autoload.php';
```

### Capabilities

* [Notification](src/Module/Notification/README.md).
* [Mailer](src/Module/Mailer/README.md).
* [Report](src/Module/Report/README.md).
* [Rest Client](src/Module/RestClient/README.md).
* [Logging](src/Core/Log/README.md).

## Licencia

Grimlock is licensed under the GNU v3.

