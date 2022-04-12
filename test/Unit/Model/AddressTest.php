<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\ValidationException;

class AddressTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $addr = new Address('Street 1', 'Suburb 2', 'Apartment 3', 'Room 4', 'Miami', 'FL', '12345');

        $this->assertSame('Street 1', $addr->getAddressLine1());
        $this->assertSame('Suburb 2', $addr->getAddressLine2());
        $this->assertSame('Apartment 3', $addr->getAddressLine3());
        $this->assertSame('Room 4', $addr->getAddressLine4());
        $this->assertSame('Miami', $addr->getLocality());
        $this->assertSame('FL', $addr->getRegion());
        $this->assertSame('12345', $addr->getPostalCode());
        $this->assertSame('US', $addr->getCountry());

        $this->assertSame([
            'addressLine1' => 'Street 1',
            'addressLine2' => 'Suburb 2',
            'addressLine3' => 'Apartment 3',
            'addressLine4' => 'Room 4',
            'locality' => 'Miami',
            'region' => 'FL',
            'postalCode' => '12345',
            'country' => 'US'
        ], $addr->toArray());
    }


    public function validationProvider()
    {
        return [
            // addressLine1 missing
            [ ['', '', '', '', 'City', 'FL', '12345'], 'addressLine1', 'This value is too short. It should have 1 character or more.' ],
            // addressLine1 too long
            [ ['omNScSZL7pBjZYmgGPcFmGbGVs9sJf110IeSSeh5bPSXgb0YkfX', '', '', '', 'City', 'FL', '12345'], 'addressLine1', 'This value is too long. It should have 50 characters or less.' ],
            // locality missing
            [ ['Street 1', '', '', '', '', 'FL', '12345'], 'locality', 'This value is too short. It should have 2 characters or more.' ],
            // locality too long
            [ ['Street 1', '', '', '', 'too-long-locality-value-here', 'FL', '12345'], 'locality', 'This value is too long. It should have 27 characters or less.' ],
            // invalid state
            [ ['Street 1', '', '', '', 'City', 'XX', '12345'], 'region', 'The value is not a valid US state.' ],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(array $values, string $field, string $errorMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("Object(Rentpost\TUShareable\Model\Address).$field");
        $this->expectExceptionMessage($errorMessage);

        new Address(...$values);
    }
}
