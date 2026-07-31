<?php

namespace GorillaSoft\Grimlock\Tests\Module\Util;

use GorillaSoft\Grimlock\Core\Exception\GrimlockException;
use GorillaSoft\Grimlock\Core\Util\GrimlockList;
use PHPUnit\Framework\TestCase;

/**
 * Class GrimlockListTest
 * @package Grimlock\Test\Util
 */
class GrimlockListTest extends TestCase
{

    /**
     * @throws GrimlockException
     */
    public function testGetItem(): void
    {
        $lArray = new GrimlockList();
        $object = "Object";
        $lArray->append($object);

        $this->assertNotNull($lArray->getItem(0));
    }

    /**
     * @throws GrimlockException
     */
    public function testGetItemException(): void
    {
        $lArray = new GrimlockList();
        $this->expectException(GrimlockException::class);
        $lArray->getItem(1);
    }

}
