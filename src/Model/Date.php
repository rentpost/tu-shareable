<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a date.
 */
class Date
{

    use Validate;


    #[Assert\NotBlank(message: 'The value is not a valid date.')]
    #[Assert\Regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', 'The value is not a valid date.')]
    protected string $value;


    public function __construct(string $value)
    {
        $this->value = $value;

        $this->validate();
    }


    public function getValue(): string
    {
        return $this->value;
    }
}
