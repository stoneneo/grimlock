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
        if ($index >= 0 && $index < $size) {
            $value = $this->offsetGet($index);
            if ($value === null) {
                throw new CoreException(self::class, "Unexpected null value at valid index $index");
            }
            return $value;
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

    /**
     * @template R
     * @param callable(T): R $callback
     * @return array<int, R>
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->getArrayCopy());
    }

    /**
     *
     * @return array<int, T>
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    public function remove(int $index): void
    {
        $size = $this->count();
        if ($index >= 0 && $index < $size) {
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
