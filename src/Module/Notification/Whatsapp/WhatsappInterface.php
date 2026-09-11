<?php

namespace GorillaSoft\Grimlock\Module\Notification\Whatsapp;

use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto\Person;

interface WhatsappInterface
{
    /**
     * @param Person $person
     * @param string $message
     * @param StringMap<string>|null $params
     * @return bool
     */
    public function sendMessage(Person $person, string $message, ?StringMap $params = new StringMap()): bool;

    /**
     * @param Person $person
     * @param string $template
     * @return bool
     */
    public function sendTemplate(Person $person, string $template): bool;

}
