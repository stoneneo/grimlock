<?php

namespace GorillaSoft\Grimlock\Core\Helper;

use ReflectionException;
use ReflectionProperty;

class PropertyHelper
{

    /**
     * @throws ReflectionException
     */
    public static function isNotEmpty(object $object, string $property): bool
    {
        $reflection = new ReflectionProperty($object, $property);

        if (!$reflection->isInitialized($object)) {
            return false;
        }

        return $object->{$property} !== '' && $object->{$property} !== null;
    }

}