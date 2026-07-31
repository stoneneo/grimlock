Gorilla Soft - Grimlock
======

![Grimlock Logo](resources/grimlock.png)

# ¿Qué es Grimlock?
Es un conjunto de librerias y utilidades para PHP.

# Capacidades

* Cliente REST
* Generación de PDF en base a HTML
* Notificaciones Push Firebase
* Envío de Emails
* Logging

# Requisitos

* PHP 8.4 o superior
* Composer 2.5.5 o superior

# Dependencias

* Guzzle
* DomPDF
* PHPMailer
* PHPUnit
* Monolog
* Google Auth

# Recomendaciones

Visita el wiki para mas información:
https://github.com/GorillaSoft/grimlock-php/wiki

# Instalación

La instalación es super facil con [Composer](https://getcomposer.org/):

```bash
composer require gorilla-soft/grimlock
```

Asegúrese de que el archivo de carga automática de Composer esté cargado.

```php
// somewhere early in your project's loading, require the Composer autoloader
// see: http://getcomposer.org/doc/00-intro.md
require 'vendor/autoload.php';
```
# Como usar

## 1. Grimlock Firebase - Notificación Push con Firebase

Se debe tener en la carpeta resources la public key de firebase con el nombre firebase.json.



Enviar una notificación a una persona

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

Enviar una notificación a personas inscritas en un tópico

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

## 2 Grimlock Pdf - Generación de PDF desde HTML

```php
use GorillaSoft\Grimlock\Module\Pdf\GrimlockPdf;

$pathHtml = __DIR__ . '/../resources/template.html.php';
$pathPdf = __DIR__ . "/../resources";
$namePdf = "test.pdf";

//Seteamos los valores de las variables que se van a reemplazar en el HTML
$vars = array("name" => "Test");

$pdf = new GrimlockPdf();
//Cargamos el HTML y las variables
$pdf->loadHTML($pathHtml, $vars);
//Generamos el PDF y nos devuelve la ruta del archivo generado
$pathFilePdf = $pdf->generatePDF($namePdf, $pathPdf);
```

## 3. Grimlock Rest Client

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

## 4. Grimlock Logging

Se recomienda tener la carpeta de log fuera de la zona pública y se le deben dar los permisos de escritura.

Se debe inicializar el GrimlockLog con el nivel de log que se desea registrar y el nombre de App. Solo se inicializa una vez.
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

Grimlock PHP esta bajo Licencia MIT.
