<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\ValidationException;

class DateTest extends TestCase
{

    public function testConstructorAndGetters(): void
    {
        $date = new Date('2022-01-01');

        $this->assertSame('2022-01-01', $date->getValue());
    }


    /**
     * @return array<array<string>>
     */
    public static function validationProvider(): array
    {
        return [
            [ '' ],
            [ '123-01-01' ],
            [ 'abc' ],
        ];
    }


    #[DataProvider('validationProvider')]
    public function testValidationErrors(string $value): void
    {
        $this->expectException(ValidationException::class);
        $errorMessage = 'The value is not a valid date.';
        $this->expectExceptionMessage($errorMessage);

        new Date($value);
    }
}
