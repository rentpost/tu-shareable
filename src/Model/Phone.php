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


    #[Assert\NotBlank]
    #[Assert\Regex('/^[0-9]{10,15}$/', 'The value is not a valid phone number.')]
    protected string $number;

    #[Assert\NotBlank]
    #[Assert\Choice(['Mobile', 'Home', 'Office'])]
    protected string $type;


    public function __construct(string $number, string $type)
    {
        $this->number = $number;
        $this->type = $type;

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


    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'phoneNumber' => $this->number,
            'phoneType' => $this->type,
        ];
    }
}
