<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Address;

class AddressTest extends TestCase
{

    public function testValidateLine1()
    {
        $addr = new Address('Rentpost\TUShareable\ModelRentpost\TUShareable\ModelRentpost\TUShareable\Model', '', '', '', '', '', '');
    }
}
