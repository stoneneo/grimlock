<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Dto;

class Sender
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }

}
