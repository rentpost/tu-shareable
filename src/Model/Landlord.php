<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a landlord.
 */
class Landlord
{

    use Validate;


    protected ?int $id = null;

    protected Email $emailAddress;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    protected string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    protected string $lastName;

    protected Phone $phone;

    protected string $businessName;

    protected Address $businessAddress;

    #[Assert\IsTrue(message: 'Terms and conditions need to be accepted.')]
    protected bool $acceptedTermsAndConditions;


    public function __construct(
        Email $emailAddress,
        string $firstName,
        string $lastName,
        Phone $phone,
        string $businessName,
        Address $businessAddress,
        bool $acceptedTermsAndConditions
    ) {
        $this->emailAddress = $emailAddress;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->businessName = $businessName;
        $this->businessAddress = $businessAddress;
        $this->acceptedTermsAndConditions = $acceptedTermsAndConditions;

        $this->validate();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getEmail(): Email
    {
        return $this->emailAddress;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function getPhone(): Phone
    {
        return $this->phone;
    }


    public function getBusinessName(): string
    {
        return $this->businessName;
    }


    public function getBusinessAddress(): Address
    {
        return $this->businessAddress;
    }


    public function getAcceptedTermsAndConditions(): bool
    {
        return $this->acceptedTermsAndConditions;
    }


    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
