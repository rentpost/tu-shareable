<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\SocialSecurityNumber;
use Rentpost\TUShareable\ValidationException;

class SocialSecurityNumberTest extends TestCase
{

    public function testConstructorAndGetters(): void
    {
        $ssn = new SocialSecurityNumber('123456789');

        $this->assertSame('123456789', $ssn->getValue());
    }


    /** @return array<array<int, string>>*/
    public static function validationProvider(): array
    {
        return [
            [ '', 'This value should not be blank.' ],
            // too short
            [ '12345678', 'The value is not a valid social security number.' ],
            // too long
            [ '1234567890', 'The value is not a valid social security number.' ],
        ];
    }


    #[DataProvider('validationProvider')]
    public function testValidationErrors(string $ssn, string $errorMessage): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        new SocialSecurityNumber($ssn);
    }
}
