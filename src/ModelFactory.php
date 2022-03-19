<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\Model\Bundle;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\Model\Person;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\Model\Property;
use Rentpost\TUShareable\Model\Renter;
use Rentpost\TUShareable\Model\SocialSecurityNumber;

class ModelFactory
{

    /**
     * @param string[] $data
     */
    public function make(string $name, array $data): object
    {
        $object = match ($name) {
            Address::class => $this->makeAddress($data),
            Bundle::class => $this->makeBundle($data),
            Landlord::class => $this->makeLandlord($data),
            Property::class => $this->makeProperty($data),
            Renter::class => $this->makeRenter($data),
            default => null
        };

        if ($object) {
            return $object;
        }

        throw new ClientException("Unrecognized class in ModelFactory: $name");
    }


    /**
     * @param string[] $data
     */
    protected function makeAddress(array $data): Address
    {
        return new Address(
            $data['addressLine1'],
            $data['addressLine2'] ?? null,
            $data['addressLine3'] ?? null,
            $data['addressLine4'] ?? null,
            $data['locality'],
            $data['region'],
            $data['postalCode'],
            $data['country']
        );
    }


    /**
     * @param string[] $data
     */
    protected function makeBundle(array $data): Bundle
    {
        return new Bundle($data['bundleId'], $data['name']);
    }


    /**
     * @param string[] $data
     */
    protected function makeLandlord(array $data): Landlord
    {
        $landlord = new Landlord(
            new Email($data['emailAddress']),
            $data['firstName'],
            $data['lastName'],
            new Phone($data['phoneNumber'], $data['phoneType']),
            $data['businessName'] ?? null,
            $this->makeAddress($data['businessAddress']),
            boolval($data['acceptedTermsAndConditions'])
        );

        $landlord->setLandlordId($data['landlordId'] ?? null);

        return $landlord;
    }


    /**
     * @param string[] $data
     */
    protected function makePerson(array $data): Person
    {
        $ssn = $data['socialSecurityNumber'] ?? null;
        $dob = $data['dateOfBirth'] ?? null;

        $person = new Person(
            new Email($data['emailAddress']),
            $data['firstName'],
            $data['middleName'] ?? null,
            $data['lastName'],
            new Phone($data['phoneNumber'], $data['phoneType']),
            $ssn ? new SocialSecurityNumber($ssn) : null,
            $dob ? new Date($dob) : null,
            $this->makeAddress($data['homeAddress']),
            boolval($data['acceptedTermsAndConditions'])
        );

        $person->setPersonId($data['personId'] ?? null);

        return $person;
    }


    /**
     * @param string[] $data
     */
    protected function makeProperty(array $data): Property
    {
        $property = new Property(
            $data['propertyName'],
            new Money((string)$data['rent']),
            new Money((string)$data['deposit']),
            $this->makeAddress($data),
            boolval($data['bankruptcyCheck']),
            $data['bankruptcyTimeFrame'],
            $data['incomeToRentRatio']
        );

        $property->setPropertyId($data['propertyId'] ?? null);
        $property->setIsActive(boolval($data['isActive']));

        return $property;
    }


    /**
     * @param string[] $data
     */
    protected function makeRenter(array $data): Renter
    {
        $msex = $data['multiShareExpirationDate'] ?? null;

        $renter = new Renter(
            $this->makePerson($data['person']),
            new Money((string)$data['income']),
            $data['incomeFrequency'],
            new Money((string)$data['otherIncome']),
            $data['otherIncomeFrequency'],
            new Money((string)$data['assets']),
            $data['employmentStatus'],
            $msex ? new Date($msex) : null
        );

        return $renter;
    }
}
