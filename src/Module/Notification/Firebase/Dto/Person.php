<?php

namespace GorillaSoft\Grimlock\Module\Notification\Firebase\Dto;

class Person
{
    public function __construct(
        public ?string $name = '',
        public ?string $lastname = '',
        public ?string $idRegistration = '',
    ) {
    }

}
