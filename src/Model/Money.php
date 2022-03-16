<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents currency.
 */
class Money
{

    use Validate;


    #[Assert\NotBlank(message: 'The value is not a valid currency amount.')]
    #[Assert\Regex('/^-?[0-9]{1,10}\\.?[0-9]{0,2}$/', 'The value is not a valid currency amount.')]
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
