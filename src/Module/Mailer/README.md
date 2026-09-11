![Grimlock Logo](grimlock.png)



# Module Mailer

---

## How to use


### 1. Send email from template html

```php
use GorillaSoft\Grimlock\Module\Mailer\Mailer;
use GorillaSoft\Grimlock\Module\Mailer\Settings\Settings;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Sender;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Person;
use GorillaSoft\Grimlock\Core\Collection\StringMap;

$mailer = new Mailer(new Settings('host', 950, 'user', 'pass', true, true));

$params = new StringMap();
$params->put('title', 'Email Confirmation');
$params->put('name', 'Joe Doe');
            
$html = file_get_contents(__DIR__ . '/../templates/.html');

$mailer->addHtml('Subject Email', $html, $params);
$mailer->addRecipients(
                new Sender('Webmaster', 'sender@example.com'),
                new Person('Joe Doe', 'jode.doe@example.com'));

$mailer->sendMail();
```

### 2. Send email from text


```php
use GorillaSoft\Grimlock\Module\Mailer\Mailer;
use GorillaSoft\Grimlock\Module\Mailer\Settings\Settings;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Sender;
use GorillaSoft\Grimlock\Module\Mailer\Dto\Person;
use GorillaSoft\Grimlock\Core\Collection\StringMap;

$mailer = new Mailer(new Settings('host', 950, 'user', 'pass', true, true));

$text = 'Hello :name.';
$params = new StringMap();
$params->put('name', 'Joe Doe');


$mailer->addText('Subject Email', $text, $params);
$mailer->addRecipients(
                new Sender('Webmaster', 'sender@example.com'),
                new Person('Joe Doe', 'jode.doe@example.com'));

$mailer->sendMail();
```