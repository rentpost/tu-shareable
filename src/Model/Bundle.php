<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a bundle of services.
 */
class Bundle
{

    use Validate;


    #[Assert\NotBlank]
    protected int $id;

    #[Assert\NotBlank]
    protected string $name;


    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;

        $this->validate();
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }
}
