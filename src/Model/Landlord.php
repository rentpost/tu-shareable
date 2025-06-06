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


    private ?int $landlordId = null;


    public function __construct(
        #[Assert\NotBlank]
        private Email $emailAddress,

        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 50)]
        private string $firstName,

        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 50)]
        private string $lastName,

        #[Assert\NotBlank]
        private Phone $phone,

        #[Assert\Length(min: 2, max: 50)]
        private ?string $businessName,

        #[Assert\NotBlank]
        private Address $businessAddress,

        #[Assert\IsTrue(message: 'Terms and conditions need to be accepted.')]
        private bool $acceptedTermsAndConditions,
    ) {
        $this->validate();
    }


    public function setLandlordId(?int $landlordId): void
    {
        $this->landlordId = $landlordId;
    }


    public function getLandlordId(): ?int
    {
        return $this->landlordId;
    }


    public function setEmail(Email $email): void
    {
        $this->emailAddress = $email;
    }


    public function getEmail(): Email
    {
        return $this->emailAddress;
    }


    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function setPhone(Phone $phone): void
    {
        $this->phone = $phone;
    }


    public function getPhone(): Phone
    {
        return $this->phone;
    }


    public function setBusinessName(?string $businessName): void
    {
        $this->businessName = $businessName;
    }


    public function getBusinessName(): ?string
    {
        return $this->businessName;
    }


    public function setBusinessAddress(Address $businessAddress): void
    {
        $this->businessAddress = $businessAddress;
    }


    public function getBusinessAddress(): Address
    {
        return $this->businessAddress;
    }


    public function setAcceptedTermsAndConditions(bool $acceptedTermsAndConditions): void
    {
        $this->acceptedTermsAndConditions = $acceptedTermsAndConditions;
    }


    public function getAcceptedTermsAndConditions(): bool
    {
        return $this->acceptedTermsAndConditions;
    }


    /** @return string[] */
    public function toArray(): array
    {
        $array = [];

        if ($this->landlordId) {
            $array['landlordId'] = $this->landlordId;
        }

        $array['emailAddress'] = $this->emailAddress->getValue();
        $array['firstName'] = $this->firstName;
        $array['lastName'] = $this->lastName;

        $array = array_merge($array, $this->phone->toArray());

        if ($this->businessName) {
            $array['businessName'] = $this->businessName;
        }

        $array['businessAddress'] = $this->businessAddress->toArray();

        $array['acceptedTermsAndConditions'] = $this->acceptedTermsAndConditions;

        return $array;
    }


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $landlord = new self(
            new Email($data['emailAddress']),
            $data['firstName'],
            $data['lastName'],
            new Phone($data['phoneNumber'], $data['phoneType']),
            $data['businessName'] ?? null,
            Address::fromArray($data['businessAddress']),
            boolval($data['acceptedTermsAndConditions']),
        );

        $landlord->setLandlordId($data['landlordId'] ?? null);

        return $landlord;
    }
}
