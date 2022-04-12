<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\ValidationException;

class MoneyTest extends TestCase
{

    public function validProvider()
    {
        return [
            [ '0' ],
            [ '0.01' ],
            [ '-0.01' ],
            [ '123' ],
            [ '-123' ],
            [ '123.45' ],
            [ '-123.45' ],
        ];
    }


    /**
     * @dataProvider validProvider
     */
    public function testValidValues(string $value)
    {
        $money = new Money($value);

        $this->assertSame($value, $money->getValue());
    }


    public function invalidProvider()
    {
        return [
            [ '' ],
            [ 'a' ],
            [ '1a2' ],

            // too many decimals
            [ '1.234' ]
        ];
    }


    /**
     * @dataProvider invalidProvider
     */
    public function testInvalidValues(string $value)
    {
        $errorMessage = 'The value is not a valid currency amount.';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        new Money($value);
    }
}
