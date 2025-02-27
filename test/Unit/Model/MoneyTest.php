<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\ValidationException;

class MoneyTest extends TestCase
{

    /** @return array<array<string>> */
    public static function validProvider(): array
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


    #[DataProvider('validProvider')]
    public function testValidValues(string $value): void
    {
        $money = new Money($value);

        $this->assertSame($value, $money->getValue());
    }


    /** @return array<array<string>> */
    public static function invalidProvider(): array
    {
        return [
            [ '' ],
            [ 'a' ],
            [ '1a2' ],

            // too many decimals
            [ '1.234' ],
        ];
    }


    #[DataProvider('invalidProvider')]
    public function testInvalidValues(string $value): void
    {
        $errorMessage = 'The value is not a valid currency amount.';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        new Money($value);
    }
}
