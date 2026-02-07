<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a phone number.
 */
class Phone
{

    use Validate;


    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Regex('/^[0-9]{10,15}$/', 'The value is not a valid phone number.')]
        private string $number,

        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['Mobile', 'Home', 'Office'])]
        private string $type,
    ) {
        $this->validate();
    }


    public function getNumber(): string
    {
        return $this->number;
    }


    public function getType(): string
    {
        return $this->type;
    }


    /** @return string[] */
    public function toArray(): array
    {
        return [
            'phoneNumber' => $this->number,
            'phoneType' => $this->type,
        ];
    }
}
