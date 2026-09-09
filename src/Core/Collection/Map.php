<?php

namespace GorillaSoft\Grimlock\Core\Collection;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @template V
 * @implements IteratorAggregate<string, V>
 */
abstract class Map implements IteratorAggregate
{

    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function remove(string $key): void
    {
        unset($this->items[$key]);
    }

    public function size(): int
    {
        return count($this->items);
    }

    public function contain(string $key): bool
    {
        $item = $this->items[$key];
        return isset($item);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function isEmpty(): bool
    {
        if (count($this->items) === 0)
        {
            return true;
        }
        return false;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

}