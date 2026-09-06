<?php

namespace GorillaSoft\Grimlock\Module\Notification\Whatsapp\Dto;

class Person
{

    public string $name {
        get {
            return $this->name;
        }
        set {
            $this->name = $value;
        }
    }

    public string $number {
        get {
            return $this->number;
        }
        set {
            $this->number = $value;
        }
    }

}