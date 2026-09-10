<?php

namespace GorillaSoft\Grimlock\Core\Collection;

use GorillaSoft\Grimlock\Core\Exception\CoreException;

/**
 * @template V
 * @extends Map<V>
 */
class HashMap extends Map
{
    public function put(string $key, mixed $value): void
    {
        $this->items[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws CoreException
     */
    public function get(string $key): mixed
    {
        if (!array_key_exists($key, $this->items)) {
            throw new CoreException(self::class, "Key '$key' not found in HashMap");
        }
        return $this->items[$key];
    }

}
