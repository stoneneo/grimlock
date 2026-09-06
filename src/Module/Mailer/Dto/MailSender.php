<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Dto;

class MailSender
{

    public string $email {
        get {
            return $this->email;
        }
        set {
            $this->email = $value;
        }
    }
    public string $name {
        get {
            return $this->name;
        }
        set {
            $this->name = $value;
        }
    }

}
