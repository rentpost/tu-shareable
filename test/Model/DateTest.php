<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\ValidationException;

class DateTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $date = new Date('2022-01-01');

        $this->assertSame('2022-01-01', $date->getValue());
    }


    public function validationProvider()
    {
        return [
            [ '' ],
            [ '123-01-01' ],
            [ 'abc' ],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(string $value)
    {
        $this->expectException(ValidationException::class);
        $errorMessage = 'The value is not a valid date.';
        $this->expectExceptionMessage($errorMessage);

        new Date($value);
    }
}
