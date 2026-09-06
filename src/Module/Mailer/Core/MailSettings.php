<?php

namespace GorillaSoft\Grimlock\Module\Mailer\Core;

class MailSettings
{

    public string $host {
        get {
            return $this->host;
        }
        set {
            $this->host = $value;
        }
    }

    public int $port {
        get {
            return $this->port;
        }
        set {
            $this->port = $value;
        }
    }

    public string $username {
        get {
            return $this->username;
        }
        set {
            $this->username = $value;
        }
    }

    public string $password {
        get {
            return $this->password;

        }
        set {
            $this->password = $value;
        }
    }

    public bool $mailAuth = true {
        get {
            return $this->mailAuth;
        }
        set {
            $this->mailAuth = $value;
        }
    }

    public bool $autoTls = false {
        get {
            return $this->autoTls;
        }
        set {
            $this->autoTls = $value;
        }
    }

}
