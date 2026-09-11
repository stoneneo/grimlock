<?php

namespace GorillaSoft\Grimlock\Tests\Core\Collection;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @throws CoreException
     */
    public function testItem(): void
    {
        $lArray = new Collection();
        $object = "Object";
        $lArray->add($object);

        $this->assertNotNull($lArray->get(0));
    }

    public function testToArray(): void
    {
        $lArray = new Collection();
        $object = "Object";
        $lArray->add($object);

        $array = $lArray->jsonSerialize();
        $this > self::assertNotEmpty($array);
    }

    /**
     * @throws CoreException
     */
    public function testItemException(): void
    {
        $lArray = new Collection();
        $this->expectException(CoreException::class);
        $lArray->get(1);
    }

    /**
     * @throws CoreException
     */
    public function testItemNegative(): void
    {
        $lArray = new Collection();
        $object = "Object";
        $lArray->add($object);

        $this->expectException(CoreException::class);
        $this->assertNull($lArray->get(-1));
    }

}
