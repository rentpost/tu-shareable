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


    protected ?int $landlordId = null;

    #[Assert\NotBlank]
    protected Email $emailAddress;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    protected string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    protected string $lastName;

    #[Assert\NotBlank]
    protected Phone $phone;

    #[Assert\Length(min: 2, max: 50)]
    protected string $businessName;

    #[Assert\NotBlank]
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


    public function getLandlordId(): ?int
    {
        return $this->landlordId;
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


    public function setLandlordId(?int $landlordId): void
    {
        $this->landlordId = $landlordId;
    }
}
