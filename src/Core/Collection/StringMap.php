<?php

namespace GorillaSoft\Grimlock\Core\Collection;

/**
 * @template V
 * @extends Map<V>
 */
class StringMap extends Map
{
    public function put(string $key, string $value): void
    {
        $this->items[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->items[$key];
    }


}
