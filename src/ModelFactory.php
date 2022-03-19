<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\Model\Bundle;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\Model\Property;

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
}
