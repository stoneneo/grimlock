<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Dto;

class MailAttachment
{

    public string $base64 {
        get {
            return $this->base64;
        }
        set {
            $this->base64 = $value;
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
    public string $type {
        get {
            return $this->type;
        }
        set {
            $this->type = $value;
        }
    }

}
