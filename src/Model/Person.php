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


    protected ?int $personId = null;

    #[Assert\NotBlank]
    protected Email $emailAddress;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 15)]
    protected string $firstName;

    #[Assert\Length(min: 1, max: 15)]
    protected ?string $middleName;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 25)]
    protected string $lastName;

    #[Assert\NotBlank]
    protected Phone $phone;

    #[Assert\NotBlank]
    protected SocialSecurityNumber $socialSecurityNumber;

    #[Assert\NotBlank]
    protected Date $dateOfBirth;

    #[Assert\NotBlank]
    protected Address $homeAddress;

    #[Assert\IsTrue(message: 'Terms and conditions need to be accepted.')]
    protected bool $acceptedTermsAndConditions;


    public function __construct(
        Email $emailAddress,
        string $firstName,
        ?string $middleName,
        string $lastName,
        Phone $phone,
        SocialSecurityNumber $socialSecurityNumber,
        Date $dateOfBirth,
        Address $homeAddress,
        bool $acceptedTermsAndConditions
    ) {
        $this->emailAddress = $emailAddress;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->socialSecurityNumber = $socialSecurityNumber;
        $this->dateOfBirth = $dateOfBirth;
        $this->homeAddress = $homeAddress;
        $this->acceptedTermsAndConditions = $acceptedTermsAndConditions;

        $this->validate();
    }


    public function getPersonId(): ?int
    {
        return $this->personId;
    }


    public function getEmail(): Email
    {
        return $this->emailAddress;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function getPhone(): Phone
    {
        return $this->phone;
    }


    public function getSocialSecurityNumber(): SocialSecurityNumber
    {
        return $this->socialSecurityNumber;
    }


    public function getDateOfBirth(): Date
    {
        return $this->dateOfBirth;
    }


    public function getHomeAddress(): Address
    {
        return $this->homeAddress;
    }


    public function getAcceptedTermsAndConditions(): bool
    {
        return $this->acceptedTermsAndConditions;
    }


    public function setPersonId(?int $personId): void
    {
        $this->personId = $personId;
    }
}
