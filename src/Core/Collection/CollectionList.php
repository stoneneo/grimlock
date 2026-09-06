<?php

namespace GorillaSoft\Grimlock\Core\Collection;

use ArrayObject;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use JsonSerializable;

/**
 * Class CollectionList
 * Class that allows manipulating a list of objects
 * @package Grimlock\Util
 * @author Rubén Darío Huamaní Ucharima
 */
class CollectionList extends ArrayObject implements JsonSerializable
{

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * @param int $index
     * @return mixed
     * @throws CoreException
     */
    public function getItem(int $index): mixed
    {
        $size = $this->count();
        if ($index >= 0 && $index < $size)
        {
            return $this->offsetGet($index);
        }

        throw new CoreException(self::class, "Index Out Of Bounds");
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->count();
    }

}
