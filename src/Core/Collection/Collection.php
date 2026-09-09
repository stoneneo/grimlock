<?php

namespace GorillaSoft\Grimlock\Core\Collection;

use ArrayObject;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use JsonSerializable;

/**
 * Class Collection
 * Class that allows manipulating a list of objects
 * @template T
 * @extends ArrayObject<int, T>
 * @author Rubén Darío Huamaní Ucharima
 */
class Collection extends ArrayObject implements JsonSerializable
{

    /**
     * @return array<int, T>
     */
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * @param int $index
     * @return T
     * @throws CoreException
     */
    public function get(int $index): mixed
    {
        $size = $this->count();
        if ($index >= 0 && $index < $size)
        {
            return $this->offsetGet($index);
        }

        throw new CoreException(self::class, "Index Out Of Bounds");
    }

    /**
     * @param mixed $item
     * @return void
     */
    public function add(mixed $item): void
    {
        $this->append($item);
    }

    public function remove(int $index): void
    {
        $size = $this->count();
        if ($index >= 0 && $index < $size)
        {
            $this->offsetUnset($index);
        }
    }

    /**
     * @return int
     */
    public function size(): int
    {
        return $this->count();
    }

    public function clear(): void
    {
        $this->exchangeArray([]);
    }

}
