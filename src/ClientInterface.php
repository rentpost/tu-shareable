<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

/**
 * Interface for TransUnion - ShareAble for Rentals API.
 */
interface ClientInterface
{

    /**
     * @return Bundle[]
     */
    public function getBundles(): array;
}
