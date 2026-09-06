<?php
namespace GorillaSoft\Grimlock\Module\Mailer\Dto;

class MailParameter
{

    private string $name {
        get {
            return $this->name;
        }
        set {
            $this->name = $value;
        }
    }
    private string $value {
        get {
            return $this->value;
        }
        set {
            $this->value = $value;
        }
    }

}

