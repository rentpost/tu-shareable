<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a screening request.
 */
class ScreeningRequest
{

    use Validate;


    protected ?int $screeningRequestId = null;

    #[Assert\NotBlank]
    protected int $landlordId;

    #[Assert\NotBlank]
    protected int $propertyId;

    #[Assert\NotBlank]
    protected int $initialBundleId;

    protected ?Date $createdOn;

    protected ?Date $modifiedOn;

    protected ?string $propertyName;

    protected ?string $propertySummaryAddress;

    /**
     * @var ScreeningRequestRenter[]
     */
    protected array $screeningRequestRenters = [];


    public function __construct(
        int $landlordId,
        int $propertyId,
        int $initialBundleId,
        ?Date $createdOn = null,
        ?Date $modifiedOn = null,
        ?string $propertyName = null,
        ?string $propertySummaryAddress = null,
    ) {
        $this->landlordId = $landlordId;
        $this->propertyId = $propertyId;
        $this->initialBundleId = $initialBundleId;
        $this->createdOn = $createdOn;
        $this->modifiedOn = $modifiedOn;
        $this->propertyName = $propertyName;
        $this->propertySummaryAddress = $propertySummaryAddress;

        $this->validate();
    }


    public function addScreeningRequestRenter(ScreeningRequestRenter $renter): self
    {
        $this->screeningRequestRenters[] = $renter;

        return $this;
    }


    public function getScreeningRequestId(): ?int
    {
        return $this->screeningRequestId;
    }


    public function getLandlordId(): int
    {
        return $this->landlordId;
    }


    public function getPropertyId(): int
    {
        return $this->propertyId;
    }


    public function getInitialBundleId(): int
    {
        return $this->initialBundleId;
    }


    public function getCreatedOn(): ?Date
    {
        return $this->createdOn;
    }


    public function getModifiedOn(): ?Date
    {
        return $this->modifiedOn;
    }


    public function getPropertyName(): ?string
    {
        return $this->propertyName;
    }


    public function getPropertySummaryAddress(): ?string
    {
        return $this->propertySummaryAddress;
    }


    /**
     * @return ScreeningRequestRenter[]
     */
    public function getScreeningRequestRenters(): array
    {
        return $this->screeningRequestRenters;
    }


    public function setScreeningRequestId(?int $val): void
    {
        $this->screeningRequestId = $val;
    }


    public function setCreatedOn(?Date $val): void
    {
        $this->createdOn = $val;
    }


    public function setModifiedOn(?Date $val): void
    {
        $this->modifiedOn = $val;
    }


    /**
     * @return string[]
     */
    public function toArray(): array
    {
        $array = [];

        if ($this->screeningRequestId) {
            $array['screeningRequestId'] = $this->screeningRequestId;
        }

        $array['landlordId'] = $this->landlordId;
        $array['propertyId'] = $this->propertyId;
        $array['initialBundleId'] = $this->initialBundleId;

        if ($this->createdOn) {
            $array['createdOn'] = $this->createdOn->getValue();
        }

        if ($this->modifiedOn) {
            $array['modifiedOn'] = $this->modifiedOn->getValue();
        }

        if ($this->propertyName) {
            $array['propertyName'] = $this->propertyName;
        }

        if ($this->propertySummaryAddress) {
            $array['propertySummaryAddress'] = $this->propertySummaryAddress;
        }

        $renters = [];
        foreach ($this->screeningRequestRenters as $renter) {
            $renters[] = $renter->toArray();
        }
        $array['screeningRequestRenters'] = $renters;

        return $array;
    }
}
