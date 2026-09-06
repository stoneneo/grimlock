<?php

namespace GorillaSoft\Grimlock\Tests\Module\Util;

use GorillaSoft\Grimlock\Core\Collection\CollectionList;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionListTest
 * @package Grimlock\Test\Util
 */
class CollectionListTest extends TestCase
{

    /**
     * @throws CoreException
     */
    public function testGetItem(): void
    {
        $lArray = new CollectionList();
        $object = "Object";
        $lArray->append($object);

        $this->assertNotNull($lArray->getItem(0));
    }

    /**
     * @throws CoreException
     */
    public function testGetItemException(): void
    {
        $lArray = new CollectionList();
        $this->expectException(CoreException::class);
        $lArray->getItem(1);
    }

}
