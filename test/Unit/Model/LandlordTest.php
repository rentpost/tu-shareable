<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\ValidationException;

class LandlordTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $landlord = $this->makeObject('First', 'Last', 'Business', true);
        $landlord->setLandlordId(123);

        $this->assertSame(123, $landlord->getLandlordId());
        $this->assertSame('First', $landlord->getFirstName());
        $this->assertSame('Last', $landlord->getLastName());
        $this->assertSame('Business', $landlord->getBusinessName());
        $this->assertTrue($landlord->getAcceptedTermsAndConditions());

        $this->assertInstanceOf(Address::class, $landlord->getBusinessAddress());
        $this->assertInstanceOf(Email::class, $landlord->getEmail());
        $this->assertInstanceOf(Phone::class, $landlord->getPhone());

        $this->assertSame([
            'landlordId' => 123,
            'emailAddress' => 'test@example.com',
            'firstName' => 'First',
            'lastName' => 'Last',
            'phoneNumber' => '0123456789',
            'phoneType' => 'Home',
            'businessName' => 'Business',
            'businessAddress' => [
                'addressLine1' => 'Streetname',
                'addressLine2' => 'Apartment',
                'locality' => 'Los Angeles',
                'region' => 'CA',
                'postalCode' => '12345',
                'country' => 'US',
            ],
            'acceptedTermsAndConditions' => true,
        ], $landlord->toArray());
    }


    public function validationProvider()
    {
        return [
            // No first name
            [ '', 'Last', 'Business', true, 'This value should not be blank.' ],
            // No last name
            [ 'First', '', 'Business', true, 'This value should not be blank.' ],
            // Not accepted terms
            [ 'First', 'Last', 'Business', false, 'Terms and conditions need to be accepted.' ],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(string $firstName, string $lastName, string $businessName, bool $acceptedTerms, string $errorMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->makeObject($firstName, $lastName, $businessName, $acceptedTerms);
    }


    protected function makeObject(string $firstName, string $lastName, string $businessName, bool $acceptedTerms = true): Landlord
    {
        // Address, Email and Phone are tested separately. Here we just give
        // them valid values for now and focus on testing other properties of Landlord.

        $address = new Address('Streetname', 'Apartment', '', '', 'Los Angeles', 'CA', '12345');
        $email = new Email('test@example.com');
        $phone = new Phone('0123456789', 'Home');

        return new Landlord(
            $email,
            $firstName,
            $lastName,
            $phone,
            $businessName,
            $address,
            $acceptedTerms
        );
    }
}
