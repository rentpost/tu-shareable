<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Rentpost\TUShareable\Model\Landlord;

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
}
