<?php

namespace GorillaSoft\Grimlock\Module\Notification\Firebase;

use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Notification;
use GorillaSoft\Grimlock\Module\Notification\Firebase\Dto\Person;

interface FirebaseInterface
{
    /**
     * @param Notification $notification
     * @param StringMap<string>|null $params
     * @return bool
     */
    public function sendNotification(Notification $notification, ?StringMap $params = null): bool;

    /**
     * @param Notification $notification
     * @param Person $person
     * @param StringMap<string>|null $params
     * @return bool
     */
    public function sendNotificationPerson(Notification $notification, Person $person, ?StringMap $params = null): bool;

}
