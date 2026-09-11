<?php

namespace GorillaSoft\Grimlock\Module\Notification\Firebase\Dto;

class Notification
{
    public function __construct(
        public string $title,
        public ?string $body = '',
        public ?string $topic = '',
        public ?string $image = ''
    ) {
    }

}
