<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents an email address.
 */
class Email
{

    use Validate;


    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        private string $address,
    ) {
        $this->validate();
    }


    public function __toString(): string
    {
        return $this->address;
    }


    public function getValue(): string
    {
        return $this->address;
    }
}
