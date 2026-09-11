<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Settings;

class Settings
{
    public function __construct(
        public string $host,
        public int $port,
        public string $username,
        public string $password,
        public bool $mailAuth = true,
        public bool $autoTls = true,
    ) {
    }

}
