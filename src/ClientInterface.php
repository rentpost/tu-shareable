<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Property;

/**
 * Interface for TransUnion - ShareAble for Rentals API.
 */
interface ClientInterface
{

    /**
     * @return Bundle[]
     */
    public function getBundles(): array;


    /*
     * Landlords
     */


    public function getLandlord(int $id): Landlord;


    public function createLandlord(Landlord $landlord): void;


    public function updateLandlord(Landlord $landlord): void;


    /*
     * Properties
     */


    public function getProperty(int $landlordId, int $propertyId): Property;


    /**
     * @return Property[]
     */
    public function getProperties(int $landlordId, int $pageNumber = 1, int $pageSize = 10): array;


    public function createProperty(int $landlordId, Property $property): void;


    public function updateProperty(int $landlordId, Property $property): void;
}
