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


    private ?int $propertyId = null;
    private bool $isActive = true;


    /**
     * Constructor
     *
     * @param int $bankruptcyTimeFrame  Time frame to go back and determine if bankruptcy is too recent
     *                                  If BankruptcyCheck is true, time frame is required, and must be between 6-120
     * @param int $incomeToRentRatio    The amount of income as compared to ratio that is sufficent for renting
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 100)]
        private string $propertyName,

        #[Assert\NotBlank]
        private Money $rent,

        #[Assert\NotBlank]
        private Money $deposit,

        #[Assert\NotBlank]
        private Address $address,
        private bool $bankruptcyCheck,
        private int $bankruptcyTimeFrame,
        private int $incomeToRentRatio,
    ) {
        $this->validate();
    }


    public function setPropertyId(?int $propertyId): void
    {
        $this->propertyId = $propertyId;
    }


    public function getPropertyId(): ?int
    {
        return $this->propertyId;
    }


    public function setPropertyName(string $propertyName): void
    {
        $this->propertyName = $propertyName;
    }


    public function getPropertyName(): string
    {
        return $this->propertyName;
    }


    public function setRent(Money $rent): void
    {
        $this->rent = $rent;
    }


    public function getRent(): Money
    {
        return $this->rent;
    }


    public function setDeposit(Money $deposit): void
    {
        $this->deposit = $deposit;
    }


    public function getDeposit(): Money
    {
        return $this->deposit;
    }


    public function setIsActive(bool $val): void
    {
        $this->isActive = $val;
    }


    public function getIsActive(): bool
    {
        return $this->isActive;
    }


    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }


    public function getAddress(): Address
    {
        return $this->address;
    }


    public function setBankruptcyCheck(bool $bankruptcyCheck): void
    {
        $this->bankruptcyCheck = $bankruptcyCheck;
    }


    public function getBankruptcyCheck(): bool
    {
        return $this->bankruptcyCheck;
    }


    public function setBankruptcyTimeFrame(int $bankruptcyTimeFrame): void
    {
        $this->bankruptcyTimeFrame = $bankruptcyTimeFrame;
    }


    public function getBankruptcyTimeFrame(): int
    {
        return $this->bankruptcyTimeFrame;
    }


    public function setIncomeToRentRatio(int $incomeToRentRatio): void
    {
        $this->incomeToRentRatio = $incomeToRentRatio;
    }


    public function getIncomeToRentRatio(): int
    {
        return $this->incomeToRentRatio;
    }


    /** @return string[] */
    public function toArray(): array
    {
        $array = [];

        if ($this->propertyId) {
            $array['propertyId'] = $this->propertyId;
        }

        $array['propertyName'] = $this->propertyName;
        $array['rent'] = $this->rent->getValue();
        $array['deposit'] = $this->deposit->getValue();
        $array['isActive'] = $this->isActive;

        $array = array_merge($array, $this->getAddress()->toArray());

        $array['bankruptcyCheck'] = $this->bankruptcyCheck;
        $array['bankruptcyTimeFrame'] = $this->bankruptcyTimeFrame;
        $array['incomeToRentRatio'] = $this->incomeToRentRatio;

        return $array;
    }


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $property = new self(
            $data['propertyName'],
            new Money((string)$data['rent']),
            new Money((string)$data['deposit']),
            Address::fromArray($data),
            boolval($data['bankruptcyCheck']),
            $data['bankruptcyTimeFrame'],
            $data['incomeToRentRatio'],
        );

        $property->setPropertyId($data['propertyId'] ?? null);
        $property->setIsActive(boolval($data['isActive']));

        return $property;
    }
}
