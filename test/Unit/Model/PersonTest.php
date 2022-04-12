<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\Model\Person;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\Model\SocialSecurityNumber;
use Rentpost\TUShareable\ValidationException;

class PersonTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $person = $this->makeObject('First', 'Middle', 'Last', true);
        $person->setPersonId(123);

        $this->assertSame(123, $person->getPersonId());
        $this->assertSame('First', $person->getFirstName());
        $this->assertSame('Middle', $person->getMiddleName());
        $this->assertSame('Last', $person->getLastName());
        $this->assertTrue($person->getAcceptedTermsAndConditions());

        $this->assertInstanceOf(Date::class, $person->getDateOfBirth());
        $this->assertInstanceOf(Email::class, $person->getEmail());
        $this->assertInstanceOf(Address::class, $person->getHomeAddress());
        $this->assertInstanceOf(Phone::class, $person->getPhone());
        $this->assertInstanceOf(SocialSecurityNumber::class, $person->getSocialSecurityNumber());

        $this->assertSame([
            'personId' => 123,
            'emailAddress' => 'test@example.com',
            'firstName' => 'First',
            'middleName' => 'Middle',
            'lastName' => 'Last',
            'phoneNumber' => '0123456789',
            'phoneType' => 'Home',
            'socialSecurityNumber' => '012345789',
            'dateOfBirth' => '1990-03-15',
            'homeAddress' => [
                'addressLine1' => 'Streetname',
                'addressLine2' => 'Apartment',
                'locality' => 'Los Angeles',
                'region' => 'CA',
                'postalCode' => '12345',
                'country' => 'US',
            ],
            'acceptedTermsAndConditions' => true,
        ], $person->toArray());
    }


    public function validationProvider()
    {
        return [
            // No first name
            [ '', 'Middle', 'Last', true, 'This value should not be blank.' ],
            // No last name
            [ 'First', 'Middle', '', true, 'This value should not be blank.' ],
            // Not accepted terms
            [ 'First', 'Middle', 'Last', false, 'Terms and conditions need to be accepted.' ],
        ];
    }


    /**
     * @dataProvider validationProvider
     */
    public function testValidationErrors(string $firstName, ?string $middleName, string $lastName, bool $acceptedTerms, string $errorMessage)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->makeObject($firstName, $middleName, $lastName, $acceptedTerms);
    }


    protected function makeObject(string $firstName, ?string $middleName, string $lastName, bool $acceptedTerms = true): Person
    {
        // Address, Date, Email, Phone and SocialSecurityNumber are tested separately. Here we just give
        // them valid values for now and focus on testing other properties of Person.

        $address = new Address('Streetname', 'Apartment', '', '', 'Los Angeles', 'CA', '12345');
        $email = new Email('test@example.com');
        $phone = new Phone('0123456789', 'Home');
        $ssn = new SocialSecurityNumber('012345789');
        $date = new Date('1990-03-15');

        return new Person(
            $email,
            $firstName,
            $middleName,
            $lastName,
            $phone,
            $ssn,
            $date,
            $address,
            $acceptedTerms
        );
    }
}
