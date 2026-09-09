<?php

namespace GorillaSoft\Grimlock\Core\Collection;

class HashMap extends Map
{

    public function put(string $key, mixed $value): void
    {
        $this->items[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $this->items[$key];
    }

}