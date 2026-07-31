![Grimlock Logo](resources/grimlock.png)

# Grimlock - Libraries and Utilities for PHP

---

## What is Grimlock?
It is a set of libraries and utilities for PHP.

## Capabilities

* REST Client
* PDF Generation from HTML
* Firebase Push Notifications
* Email Sending
* Logging

## Requirements

* PHP 8.3 or higher
* Composer 2.5.5 or higher

## Dependencies

* Guzzle
* DomPDF
* PHPMailer
* PHPUnit
* Monolog
* Google Auth

## Recommendations

Visit the wiki for more information:
https://github.com/stoneneo/grimlock-php/wiki

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
## How to use

### 1. Grimlock Firebase - Push Notification with Firebase

You must have the Firebase public key in the resources folder name firebase.json



Send a notification to a specific person

```php
use Grimlock\Module\Notification\Firebase\GrimlockFirebase;
use Grimlock\Module\Notification\Firebase\Bean\Person;
use Grimlock\Module\Notification\Firebase\Bean\Notification;

$firebaseKey = __DIR__ . '/../resources/firebase.json';
$firebaseProject = "";

$grimlockFirebase = new GrimlockFirebase($firebaseProject, $firebaseKey);

$person = new Person();
$person->name = 'Jon';
$person->lastname = 'Doe';
$person->idRegistration = '';

$notification = new Notification();
$notification->title = 'Message Push Test';
$notification->body = 'Hello {NAME} {LASTNAME}. It is a message test.';
$notification->image = $urlImage;

$grimlockFirebase->sendNotification($notification, $person);
```

Send a notification to a topic

```php
use Grimlock\Module\Notification\Firebase\GrimlockFirebase;
use Grimlock\Module\Notification\Firebase\Bean\Person;
use Grimlock\Module\Notification\Firebase\Bean\Notification;

$firebaseKey = __DIR__ . '/../resources/firebase.json';
$firebaseProject = "";
$topicId = "topic_test_id";
$urlImage = "";//URL Image Notification

$grimlockFirebase = new GrimlockFirebase($firebaseProject, $firebaseKey);

$notification = new Notification();
$notification->topic = $topicId;
$notification->title = 'Message Push Test';
$notification->body = 'Message Push Test.';
$notification->image = $urlImage;

$grimlockFirebase->sendNotification($notification);
```

### 2 Grimlock Pdf - Generate PDF from HTML

```php
use GorillaSoft\Grimlock\Module\Pdf\GrimlockPdf;

$pathHtml = __DIR__ . '/../resources/template.html.php';
$pathPdf = __DIR__ . "/../resources";
$namePdf = "test.pdf";

$vars = array("name" => "Test");

$pdf = new GrimlockPdf();
$pdf->loadHTML($pathHtml, $vars);
$pathFilePdf = $pdf->generatePDF($namePdf, $pathPdf);
```

### 3. Grimlock Rest Client

```php
use Grimlock\Module\RestClient\GrimlockRestClient;

$grimlockRestClient = new GrimlockRestClient('http://localhost:8080');
$grimlockRestClient->addHeader('Authorization', 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30');

//Return Object GrimlockResponse
$response = $grimlockRestClient->get('/customers');

if ($response->getCode() == 200) {
    $data = $response->getBody();
} 


```

### 4. Grimlock Logging

It is recommended to keep the log folder outside the public area and grant it write permissions.

GrimlockLog must be initialized with the desired log level and the App name. It only needs to be initialized once.

```php
use GorillaSoft\Grimlock\Core\Log\GrimlockLog;
use GorillaSoft\Grimlock\Core\Log\Enum\LevelLog;

$log = __DIR__ . '/../logs/logs.txt';
GrimlockLog::init($log, LevelLog::Info);
```

Luego se puede usar el GrimlockLog para registrar mensajes de log en los niveles correspondientes dependiendo de con que nivel fue instanciada la clase.

```php
GrimlockLog::error("Message Error");
GrimlockLog::debug("Message Debug");
GrimlockLog::info(("Message Info"));
```

## Licencia

Grimlock PHP is licensed under the MIT License.

