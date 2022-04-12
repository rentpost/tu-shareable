<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\Model\Property;
use Rentpost\TUShareable\ValidationException;

class PropertyTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $property = $this->makeObject('Apartment', true, 20, 90);
        $property->setPropertyId(123);

        $this->assertSame(123, $property->getPropertyId());
        $this->assertSame('Apartment', $property->getPropertyName());
        $this->assertTrue($property->getIsActive());
        $this->assertTrue($property->getBankruptcyCheck());
        $this->assertSame(20, $property->getBankruptcyTimeFrame());
        $this->assertSame(90, $property->getIncomeToRentRatio());

        $this->assertInstanceOf(Address::class, $property->getAddress());
        $this->assertInstanceOf(Money::class, $property->getRent());
        $this->assertInstanceOf(Money::class, $property->getDeposit());

        $this->assertSame([
            'propertyId' => 123,
            'propertyName' => 'Apartment',
            'rent' => '500',
            'deposit' => '1000',
            'isActive' => true,
            'addressLine1' => 'Streetname',
            'addressLine2' => 'Apartment',
            'locality' => 'Los Angeles',
            'region' => 'CA',
            'postalCode' => '12345',
            'country' => 'US',
            'bankruptcyCheck' => true,
            'bankruptcyTimeFrame' => 20,
            'incomeToRentRatio' => 90,
        ], $property->toArray());
    }


    public function validationProvider()
    {
        return [
            // No name
            [ '', 'This value should not be blank.' ],
            // Too long name
            [ 'C1bj6YXjJbGyARqKRljur7mXTUWe1uyWMqECWdCICEWDUv169qU66CT4gztMc9AiRWelsynyT1jMnPsuCz9MfErN9S3XigeDIbJZn', 'This value is too long. It should have 100 characters or less.' ],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(string $propertyName, string $errorMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->makeObject($propertyName);
    }


    protected function makeObject(string $propertyName, bool $bankruptcyCheck = true, int $bankruptcyTimeFrame = 12, int $incomeToRentRatio = 50): Property
    {
        // Address and Money are tested separately. Here we just give
        // them valid values for now and focus on testing other properties.

        $rent = new Money('500');
        $deposit = new Money('1000');
        $address = new Address('Streetname', 'Apartment', '', '', 'Los Angeles', 'CA', '12345');

        return new Property(
            $propertyName,
            $rent,
            $deposit,
            $address,
            $bankruptcyCheck,
            $bankruptcyTimeFrame,
            $incomeToRentRatio
        );
    }
}
