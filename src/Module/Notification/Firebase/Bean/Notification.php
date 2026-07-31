<?php

namespace GorillaSoft\Grimlock\Module\Notification\Firebase\Bean;

class Notification
{

    public string $topic {
        get {
            return $this->topic;
        }
        set {
            $this->topic = $value;
        }
    }
    public string $title {
        get {
            return $this->title;
        }
        set {
            $this->title = $value;
        }
    }
    public ?string $body {
        get {
            return $this->body ?? '';
        }
        set {
            $this->body = $value;
        }
    }

    public ?string $image {
        get {
            return $this->image ?? '';
        }
        set {
            $this->image = $value;
        }
    }
}
