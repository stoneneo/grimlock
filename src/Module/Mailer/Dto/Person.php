<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Dto;

/**
 *
 */
class Person
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }

}
