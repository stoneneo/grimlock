![Grimlock Logo](grimlock.png)


# Module Notification - Firebase

---

## How to use

### Push Notification with Firebase

You must have the Firebase public key in the resources folder

1. Send a notification to a specific person

```php

use GorillaSoft\Grimlock\Module\Notification\Firebase\Firebase;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Person;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;

$firebase = new Firebase(
                    'project_firebase_id', 
                    '/../resources/google-services.json');

$person = new Person(
                'Joe', 
                'Doe', 
                'fcm_registration_id');

$notification = new Notification(
                        'Message Push Test',
                        'Hello. It is a message test.',
                        '',
                        'https://example.com/image.jpg');                      

$firebase->sendPerson($notification, $person);
```

2. Send a notification to a specific person with parameters

```php

use GorillaSoft\Grimlock\Module\Notification\Firebase\Firebase;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Person;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;
use GorillaSoft\Grimlock\Core\Collection\StringMap;

$firebase = new Firebase(
                    'project_firebase_id', 
                    '/../resources/google-services.json');

$person = new Person(
                'Joe', 
                'Doe', 
                'fcm_registration_id');

$notification = new Notification(
                        'Message Push Test',
                        'Hello :name :lastname. It is a message test.',
                        '',
                        'https://example.com/image.jpg');
                        
$params = new StringMap();
$params->put('name', 'Joe');
$params->put('lastname', 'Doe');                                          

$firebase->sendPerson($notification, $person, $params);
```

3. Send a notification to a topic

```php
use GorillaSoft\Grimlock\Module\Notification\Firebase\Firebase;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;

$firebase = new Firebase(
                    'project_firebase_id', 
                    '/../resources/google-services.json');

$notification = new Notification(
                        'Message Push Test',
                        'Hello. It is a message test.',
                        'topic_id',
                        'https://example.com/image.jpg');

$firebase->sendTopic($notification);
```
Send a notification to a topic

```php
use GorillaSoft\Grimlock\Module\Notification\Firebase\Firebase;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;
use GorillaSoft\Grimlock\Core\Collection\StringMap;

$firebase = new Firebase(
                    'project_firebase_id', 
                    '/../resources/google-services.json');

$notification = new Notification(
                        'Message Push Test',
                        'Hello :name :lastname. It is a message test.',
                        'topic_id',
                        'https://example.com/image.jpg');

$params = new StringMap();
$params->put('name', 'Joe');
$params->put('lastname', 'Doe');    

$firebase->sendTopic($notification, $params);
```
