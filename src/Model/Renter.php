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


    public function __construct(
        #[Assert\NotBlank]
        private Person $person,

        #[Assert\NotBlank]
        private Money $income,

        #[Assert\NotBlank]
        #[Assert\Choice(['PerMonth', 'PerYear'])]
        private string $incomeFrequency,

        #[Assert\NotBlank]
        private Money $otherIncome,

        #[Assert\NotBlank]
        #[Assert\Choice(['PerMonth', 'PerYear'])]
        private string $otherIncomeFrequency,

        #[Assert\NotBlank]
        private Money $assets,

        private EmploymentStatus $employmentStatus,

        private ?Date $multiShareExpirationDate = null,
    ) {
        $this->validate();
    }


    // For convenience, id is stored in Person
    public function setRenterId(?int $renterId): void
    {
        $this->person->setPersonId($renterId);
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


    public function setIncome(Money $income): void
    {
        $this->income = $income;
    }


    public function getIncome(): Money
    {
        return $this->income;
    }


    public function setIncomeFrequency(string $incomeFrequency): void
    {
        $this->incomeFrequency = $incomeFrequency;
    }


    public function getIncomeFrequency(): string
    {
        return $this->incomeFrequency;
    }


    public function setOtherIncome(Money $otherIncome): void
    {
        $this->otherIncome = $otherIncome;
    }


    public function getOtherIncome(): Money
    {
        return $this->otherIncome;
    }


    public function setOtherIncomeFrequency(string $otherIncomeFrequency): void
    {
        $this->otherIncomeFrequency = $otherIncomeFrequency;
    }


    public function getOtherIncomeFrequency(): string
    {
        return $this->otherIncomeFrequency;
    }


    public function setAssets(Money $assets): void
    {
        $this->assets = $assets;
    }


    public function getAssets(): Money
    {
        return $this->assets;
    }


    public function setEmploymentStatus(EmploymentStatus $employmentStatus): void
    {
        $this->employmentStatus = $employmentStatus;
    }


    public function getEmploymentStatus(): EmploymentStatus
    {
        return $this->employmentStatus;
    }


    public function setMultiShareExpirationDate(?Date $multiShareExpirationDate): void
    {
        $this->multiShareExpirationDate = $multiShareExpirationDate;
    }


    public function getMultiShareExpirationDate(): ?Date
    {
        return $this->multiShareExpirationDate;
    }


    /** @return string[] */
    public function toArray(): array
    {
        $array = $this->person->toArray();

        $array['income'] = $this->income->getValue();
        $array['incomeFrequency'] = $this->incomeFrequency;
        $array['otherIncome'] = $this->otherIncome->getValue();
        $array['otherIncomeFrequency'] = $this->otherIncomeFrequency;
        $array['assets'] = $this->assets->getValue();
        $array['employmentStatus'] = $this->employmentStatus->name;

        if ($this->multiShareExpirationDate) {
            $array['multiShareExpirationDate'] = $this->multiShareExpirationDate->getValue();
        }

        return $array;
    }


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $msex = $data['multiShareExpirationDate'] ?? null;

        $renter = new self(
            Person::fromArray($data),
            new Money((string)$data['income']),
            $data['incomeFrequency'],
            new Money((string)$data['otherIncome']),
            $data['otherIncomeFrequency'],
            new Money((string)$data['assets']),
            EmploymentStatus::from($data['employmentStatus']),
            $msex ? new Date($msex) : null,
        );

        $renter->setRenterId($data['renterId'] ?? null);

        return $renter;
    }
}
