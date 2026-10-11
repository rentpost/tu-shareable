<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\Attributes\DataProvider;
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

    protected function makeObject(
        string $firstName,
        ?string $middleName,
        string $lastName,
        bool $acceptedTerms = true,
    ): Person
    {
        // Address, Date, Email, Phone and SocialSecurityNumber are tested separately. Here we just give
        // them valid values for now and focus on testing other properties of Person.

        $address = new Address('Streetname', 'Apartment', '', '', 'Los Angeles', 'CA', '12345');
        $email = new Email('test@example.com');
        $phone = new Phone('0123456789', 'Home');
        $nationalId = new SocialSecurityNumber('012345789');
        $date = new Date('1990-03-15');

        return new Person(
            $email,
            $firstName,
            $middleName,
            $lastName,
            $phone,
            $nationalId,
            $date,
            $address,
            $acceptedTerms,
        );
    }


    /** @return array<array<string, mixed>> */
    public static function validationProvider(): array
    {
        return [
            // No first name
            [ '', 'Middle', 'Last', true, 'This value should not be blank.' ],
            // No last name
            [ 'First', 'Middle', '', true, 'This value should not be blank.' ],
            // Not accepted terms
            [ 'First', 'Middle', 'Last', false, 'Terms and conditions need to be accepted.' ],
            // First name too long
            [ str_repeat('a', 51), 'Middle', 'Last', true, 'It should have 50 characters or less.' ],
            // Middle name too long
            [ 'First', str_repeat('a', 51), 'Last', true, 'It should have 50 characters or less.' ],
            // Last name too long
            [ 'First', 'Middle', str_repeat('a', 51), true, 'It should have 50 characters or less.' ],
            // Invalid characters
            [ 'First!', 'Middle', 'Last', true, Person::NAME_PATTERN_MESSAGE ],
            [ 'First', 'Middle"', 'Last', true, Person::NAME_PATTERN_MESSAGE ],
            [ 'First', 'Middle', 'L@st', true, Person::NAME_PATTERN_MESSAGE ],
        ];
    }


    public function testFiftyCharacterNamesWithAllowedPunctuationAreValid(): void
    {
        $firstName = str_pad("Mary-Jo O'Neil, Jr.", 50, 'a');
        $person = $this->makeObject($firstName, str_repeat('b', 50), str_repeat('c', 50));

        $this->assertSame($firstName, $person->getFirstName());
    }


    public function testConstructorAndGetters(): void
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
        $this->assertInstanceOf(SocialSecurityNumber::class, $person->getNationalId());

        $this->assertSame([
            'personId' => 123,
            'emailAddress' => 'test@example.com',
            'firstName' => 'First',
            'middleName' => 'Middle',
            'lastName' => 'Last',
            'phoneNumber' => '0123456789',
            'phoneType' => 'Home',
            'nationalId' => '012345789',
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


    public function testReportRequestCutsNamesToItsLengths(): void
    {
        $person = $this->makeObject('Anna Maria Lou Beth', str_repeat('m', 20), str_repeat('l', 30));

        $reportRequest = $person->toReportRequestArray();

        // Cut at 15, "Anna Maria Lou " loses its trailing space
        $this->assertSame('Anna Maria Lou', $reportRequest['firstName']);
        $this->assertSame(str_repeat('m', 15), $reportRequest['middleName']);
        $this->assertSame(str_repeat('l', 25), $reportRequest['lastName']);

        // The renter itself keeps the full name
        $this->assertSame('Anna Maria Lou Beth', $person->toArray()['firstName']);
    }


    public function testReportRequestLeavesShortNamesAlone(): void
    {
        $person = $this->makeObject('First', null, 'Last');

        $this->assertSame($person->toArray(), $person->toReportRequestArray());
    }


    #[DataProvider('validationProvider')]
    public function testValidationErrors(
        string $firstName,
        ?string $middleName,
        string $lastName,
        bool $acceptedTerms,
        string $errorMessage,
    ): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->makeObject($firstName, $middleName, $lastName, $acceptedTerms);
    }
}
