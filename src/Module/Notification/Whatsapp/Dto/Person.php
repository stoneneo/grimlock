<?php

namespace GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto;

class Person
{
    public function __construct(
        public string $name,
        public string $number,
    ) {
    }

}
