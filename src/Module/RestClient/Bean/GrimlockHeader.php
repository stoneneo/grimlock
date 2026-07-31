<?php

namespace GorillaSoft\Grimlock\Module\RestClient\Bean;

class GrimlockHeader
{

    public string $name {
        get {
            return $this->name;
        }
        set {
            $this->name = $value;
        }
    }
    public string $value {
        get {
            return $this->value;
        }
        set {
            $this->value = $value;
        }
    }

}
