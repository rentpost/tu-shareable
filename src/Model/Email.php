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


    #[Assert\NotBlank]
    #[Assert\Email]
    protected string $address;


    public function __construct(string $address)
    {
        $this->address = $address;

        $this->validate();
    }


    public function getAddress(): string
    {
        return $this->address;
    }
}
