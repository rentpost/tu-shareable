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
    protected int $bundleId;

    #[Assert\NotBlank]
    protected string $name;


    public function __construct(int $bundleId, string $name)
    {
        $this->bundleId = $bundleId;
        $this->name = $name;

        $this->validate();
    }


    public function getBundleId(): int
    {
        return $this->bundleId;
    }


    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return string[]
     */
    public function toArray(): array
    {
        return [
            'bundleId' => $this->bundleId,
            'name' => $this->name,
        ];
    }
}
