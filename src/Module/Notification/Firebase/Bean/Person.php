<?php

namespace GorillaSoft\Grimlock\Module\Notification\Firebase\Bean;

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
    public string $lastname {
        get {
            return $this->lastname;
        }
        set {
            $this->lastname = $value;
        }
    }
    public string $idRegistration {
        get {
            return $this->idRegistration;
        }
        set {
            $this->idRegistration = $value;
        }
    }

}
