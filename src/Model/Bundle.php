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


    public function __construct(
        #[Assert\NotBlank]
        private int $bundleId,
        #[Assert\NotBlank]
        private string $name,
    ) {
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


    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'bundleId' => $this->bundleId,
            'name' => $this->name,
        ];
    }


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['bundleId'],
            $data['name'],
        );
    }
}
