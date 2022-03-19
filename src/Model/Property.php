<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a property.
 */
class Property
{

    use Validate;


    protected ?int $propertyId = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 100)]
    protected string $propertyName;

    #[Assert\NotBlank]
    protected Money $rent;

    #[Assert\NotBlank]
    protected Money $deposit;

    protected bool $isActive = true;

    #[Assert\NotBlank]
    protected Address $address;

    protected bool $bankruptcyCheck;

    // Time frame to go back and determine if bankruptcy is too recent
    // If BankruptcyCheck is true, time frame is required, and must be between 6-120
    protected int $bankruptcyTimeFrame;

    // The amount of income as compared to ratio that is sufficent for renting
    protected int $incomeToRentRatio;


    public function __construct(
        string $propertyName,
        Money $rent,
        Money $deposit,
        Address $address,
        bool $bankruptcyCheck,
        int $bankruptcyTimeFrame,
        int $incomeToRentRatio
    ) {
        $this->propertyName = $propertyName;
        $this->rent = $rent;
        $this->deposit = $deposit;
        $this->address = $address;
        $this->bankruptcyCheck = $bankruptcyCheck;
        $this->bankruptcyTimeFrame = $bankruptcyTimeFrame;
        $this->incomeToRentRatio = $incomeToRentRatio;

        $this->validate();
    }


    public function getPropertyId(): ?int
    {
        return $this->propertyId;
    }


    public function getPropertyName(): string
    {
        return $this->propertyName;
    }


    public function getRent(): Money
    {
        return $this->rent;
    }


    public function getDeposit(): Money
    {
        return $this->deposit;
    }


    public function getIsActive(): bool
    {
        return $this->isActive;
    }


    public function getAddress(): Address
    {
        return $this->address;
    }


    public function getBankruptcyCheck(): bool
    {
        return $this->bankruptcyCheck;
    }


    public function getBankruptcyTimeFrame(): int
    {
        return $this->bankruptcyTimeFrame;
    }


    public function getIncomeToRentRatio(): int
    {
        return $this->incomeToRentRatio;
    }


    public function setPropertyId(?int $propertyId): void
    {
        $this->propertyId = $propertyId;
    }


    public function setIsActive(bool $val): void
    {
        $this->isActive = $val;
    }
}
