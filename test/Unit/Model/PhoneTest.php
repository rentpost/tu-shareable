<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\ValidationException;

class PhoneTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $phone = new Phone('0123456789', 'Home');

        $this->assertSame('0123456789', $phone->getNumber());
        $this->assertSame('Home', $phone->getType());

        $this->assertSame([
            'phoneNumber' => '0123456789',
            'phoneType' => 'Home',
        ], $phone->toArray());
    }


    public function validationProvider()
    {
        return [
            [ '', 'Home', 'This value should not be blank.' ],
            // too short number
            [ '123456789', 'Mobile', 'The value is not a valid phone number.' ],
            // too long number
            [ '1234567890123456', 'Mobile', 'The value is not a valid phone number.' ],
            // invalid type
            [ '1234567890', 'Unknown', 'The value you selected is not a valid choice.' ],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(string $phone, string $type, string $errorMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        new Phone($phone, $type);
    }
}
