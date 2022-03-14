<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a social security number.
 */
class SocialSecurityNumber
{

    use ValidateTrait;


    #[Assert\NotBlank]
    #[Assert\Regex("/^[0-9]{9}$/", "The value is not a valid social security number.")]
    protected string $ssn;


    public function __construct(
        string $ssn,
    ) {
        $this->ssn = $ssn;

        $this->validate();
    }


    public function getValue(): string
    {
        return $this->ssn;
    }
}
