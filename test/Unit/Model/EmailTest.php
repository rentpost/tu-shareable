<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\ValidationException;

class EmailTest extends TestCase
{

    public function testConstructorAndGetters(): void
    {
        $email = new Email('test@example.com');

        $this->assertSame('test@example.com', $email->getAddress());
    }


    /**
     * @return array<array<string>>
     */
    public static function validationProvider(): array
    {
        return [
            [ '', 'This value should not be blank.' ],
            [ 'abc', 'This value is not a valid email address.' ],
        ];
    }


    #[DataProvider('validationProvider')]
    public function testValidationErrors(string $address, string $errorMessage): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        new Email($address);
    }
}
