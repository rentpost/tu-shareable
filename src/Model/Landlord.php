<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a landlord.
 */
class Landlord
{


    public function __construct(
        protected Logger $logger,
        protected HttpClient $http
    ) { }


}
