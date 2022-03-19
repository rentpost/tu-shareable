<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a renter.
 */
class Renter
{

    use Validate;


    #[Assert\NotBlank]
    protected Person $person;

    #[Assert\NotBlank]
    protected Money $income;

    #[Assert\NotBlank]
    #[Assert\Choice(['PerMonth', 'PerYear'])]
    protected string $incomeFrequency;

    #[Assert\NotBlank]
    protected Money $otherIncome;

    #[Assert\NotBlank]
    #[Assert\Choice(['PerMonth', 'PerYear'])]
    protected string $otherIncomeFrequency;

    #[Assert\NotBlank]
    protected Money $assets;

    #[Assert\NotBlank]
    #[Assert\Choice(['NotEmployed', 'Employed', 'SelfEmployed', 'Student'])]
    protected string $employmentStatus;

    protected ?Date $multiShareExpirationDate;


    public function __construct(
        Person $person,
        Money $income,
        string $incomeFrequency,
        Money $otherIncome,
        string $otherIncomeFrequency,
        Money $assets,
        string $employmentStatus,
        ?Date $multiShareExpirationDate
    ) {
        $this->person = $person;
        $this->income = $income;
        $this->incomeFrequency = $incomeFrequency;
        $this->otherIncome = $otherIncome;
        $this->otherIncomeFrequency = $otherIncomeFrequency;
        $this->assets = $assets;
        $this->employmentStatus = $employmentStatus;
        $this->multiShareExpirationDate = $multiShareExpirationDate;

        $this->validate();
    }


    // For convenience, id is stored in Person
    public function getRenterId(): ?int
    {
        return $this->person->getPersonId();
    }


    public function getPerson(): Person
    {
        return $this->person;
    }


    public function getIncome(): Money
    {
        return $this->income;
    }


    public function getIncomeFrequency(): string
    {
        return $this->incomeFrequency;
    }


    public function getOtherIncome(): Money
    {
        return $this->otherIncome;
    }


    public function getOtherIncomeFrequency(): string
    {
        return $this->otherIncomeFrequency;
    }


    public function getAssets(): Money
    {
        return $this->assets;
    }


    public function getEmploymentStatus(): string
    {
        return $this->employmentStatus;
    }


    public function getMultiShareExpirationDate(): ?Date
    {
        return $this->multiShareExpirationDate;
    }


    // For convenience, id is stored in Person
    public function setRenterId(?int $renterId): void
    {
        $this->person->setPersonId($renterId);
    }


    /**
     * @return string[]
     */
    public function toArray(): array
    {
        $array = ['person' => $this->person->toArray()];

        $array['income'] = $this->income->getValue();
        $array['incomeFrequency'] = $this->incomeFrequency;
        $array['otherIncome'] = $this->otherIncome->getValue();
        $array['otherIncomeFrequency'] = $this->otherIncomeFrequency;
        $array['assets'] = $this->assets->getValue();
        $array['employmentStatus'] = $this->employmentStatus;

        if ($this->multiShareExpirationDate) {
            $array['multiShareExpirationDate'] = $this->multiShareExpirationDate->getValue();
        }

        return $array;
    }
}
