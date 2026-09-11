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
    public function sendTopic(Notification $notification, ?StringMap $params = null): bool;

    /**
     * @param Notification $notification
     * @param Person $person
     * @param StringMap<string>|null $params
     * @return bool
     */
    public function sendPerson(Notification $notification, Person $person, ?StringMap $params = null): bool;

}
