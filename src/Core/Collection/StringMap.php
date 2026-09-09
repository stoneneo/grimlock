<?php

namespace GorillaSoft\Grimlock\Core\Collection;

class StringMap extends Map
{
    public function put(string $key, string $value): void
    {
        $this->items[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->items[$key];
    }


}