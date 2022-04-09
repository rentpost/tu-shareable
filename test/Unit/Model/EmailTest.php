<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\ValidationException;

class EmailTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $email = new Email('test@example.com');

        $this->assertSame('test@example.com', $email->getAddress());
    }


    public function validationProvider()
    {
        return [
            [ '', 'This value should not be blank.' ],
            [ 'abc', 'This value is not a valid email address.' ],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(string $address, string $errorMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        new Email($address);
    }
}
