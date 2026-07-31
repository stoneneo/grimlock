<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Bean;

class MailAttachment
{

    private string $base64 {
        get {
            return $this->base64;
        }
        set {
            $this->base64 = $value;
        }
    }
    private string $name {
        get {
            return $this->name;
        }
        set {
            $this->name = $value;
        }
    }
    private string $type {
        get {
            return $this->type;
        }
        set {
            $this->type = $value;
        }
    }

}
