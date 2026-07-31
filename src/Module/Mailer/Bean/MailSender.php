<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Bean;

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
