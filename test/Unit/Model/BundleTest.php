<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Bundle;

class BundleTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $bundle = new Bundle(123, 'Name');

        $this->assertSame(123, $bundle->getBundleId());
        $this->assertSame('Name', $bundle->getName());

        $this->assertSame([
            'bundleId' => 123,
            'name' => 'Name'
        ], $bundle->toArray());
    }
}
