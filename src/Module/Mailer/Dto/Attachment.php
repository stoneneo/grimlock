<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Dto;

class Attachment
{
    public function __construct(
        public string $name,
        public string $type,
        public string $base64,
    ) {
    }

}
