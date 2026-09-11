![Grimlock Logo](grimlock.png)


# Module Notification - Whatsapp

---

## How to use

### Send Message with Whatsapp

You must have the Firebase public key in the resources folder

1. Send a message to a person

```php

use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Whatsapp;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto\Person;
use GorillaSoft\Grimlock\Core\Collection\StringMap;

$whatsapp = new Whatsapp(
                    'access_token_whatsapp', 
                    'phone_number_whatsapp');

$person = new Person(
                'Joe', 
                '+019999999');

$whatsapp->sendMessage($person, 'Message Test');
```

2. Send a message with parameters to a person

```php

use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Whatsapp;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto\Person;

$whatsapp = new Whatsapp(
                    'access_token_whatsapp', 
                    'phone_number_whatsapp');

$person = new Person(
                'Joe', 
                '+019999999');
                
$params = new StringMap();
$params->put('name', 'Joe');
$params->put('lastname', 'Doe');   

$whatsapp->sendMessage($person, 'Message Test. Hello :name :lastname', $params);
```

3. Send a message template to a person

```php

use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Whatsapp;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto\Person;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Enum\Language;

$whatsapp = new Whatsapp(
                    'access_token_whatsapp', 
                    'phone_number_whatsapp');

$person = new Person(
                'Joe', 
                '+019999999');

$whatsapp->sendTemplate($person, 'template_id', Language::EN);
```

