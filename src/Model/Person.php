<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a person.
 */
class Person
{

    use Validate;


    private ?int $personId = null;


    public function __construct(
        #[Assert\NotBlank]
        private Email $emailAddress,

        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 15)]
        private string $firstName,

        #[Assert\Length(min: 1, max: 15)]
        private ?string $middleName,

        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 25)]
        private string $lastName,

        #[Assert\NotBlank]
        private Phone $phone,

        private ?SocialSecurityNumber $nationalId,
        private ?Date $dateOfBirth,

        #[Assert\NotBlank]
        private Address $homeAddress,

        #[Assert\IsTrue(message: 'Terms and conditions need to be accepted.')]
        private bool $acceptedTermsAndConditions,
    ) {
        $this->validate();
    }


    public function setPersonId(?int $personId): void
    {
        $this->personId = $personId;
    }


    public function getPersonId(): ?int
    {
        return $this->personId;
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


    public function setMiddleName(?string $middleName): void
    {
        $this->middleName = $middleName;
    }


    public function getMiddleName(): ?string
    {
        return $this->middleName;
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


    public function setNationalId(?SocialSecurityNumber $nationalId): void
    {
        $this->nationalId = $nationalId;
    }


    public function getNationalId(): ?SocialSecurityNumber
    {
        return $this->nationalId;
    }


    public function setDateOfBirth(?Date $date): void
    {
        $this->dateOfBirth = $date;
    }


    public function getDateOfBirth(): ?Date
    {
        return $this->dateOfBirth;
    }


    public function setHomeAddress(Address $homeAddress): void
    {
        $this->homeAddress = $homeAddress;
    }


    public function getHomeAddress(): Address
    {
        return $this->homeAddress;
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

        if ($this->personId) {
            $array['personId'] = $this->personId;
        }

        $array['emailAddress'] = $this->emailAddress->getValue();
        $array['firstName'] = $this->firstName;

        if ($this->middleName) {
            $array['middleName'] = $this->middleName;
        }

        $array['lastName'] = $this->lastName;

        $array = array_merge($array, $this->phone->toArray());

        if ($this->nationalId) {
            $array['nationalId'] = $this->nationalId->getValue();
        }

        if ($this->dateOfBirth) {
            $array['dateOfBirth'] = $this->dateOfBirth->getValue();
        }

        $array['homeAddress'] = $this->homeAddress->toArray();
        $array['acceptedTermsAndConditions'] = $this->acceptedTermsAndConditions;

        return $array;
    }


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $ssn = $data['socialSecurityNumber'] ?? null;
        $dob = $data['dateOfBirth'] ?? null;

        $person = new self(
            new Email($data['emailAddress']),
            $data['firstName'],
            $data['middleName'] ?? null,
            $data['lastName'],
            new Phone($data['phoneNumber'], $data['phoneType']),
            $ssn ? new SocialSecurityNumber($ssn) : null,
            $dob ? new Date($dob) : null,
            Address::fromArray($data['homeAddress']),
            boolval($data['acceptedTermsAndConditions']),
        );

        $person->setPersonId($data['personId'] ?? null);

        return $person;
    }
}
