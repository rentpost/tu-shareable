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


    private ?int $screeningRequestId = null;

    /** @var ScreeningRequestRenter[] */
    private array $screeningRequestRenters = [];


    public function __construct(
        #[Assert\NotBlank]
        private int $landlordId,

        #[Assert\NotBlank]
        private int $propertyId,

        #[Assert\NotBlank]
        private int $initialBundleId,

        private ?Date $createdOn = null,
        private ?Date $modifiedOn = null,
        private ?string $propertyName = null,
        private ?string $propertySummaryAddress = null,
    ) {
        $this->validate();
    }


    public function setScreeningRequestId(?int $val): void
    {
        $this->screeningRequestId = $val;
    }


    public function getScreeningRequestId(): ?int
    {
        return $this->screeningRequestId;
    }


    public function addScreeningRequestRenter(ScreeningRequestRenter $renter): self
    {
        $this->screeningRequestRenters[] = $renter;

        return $this;
    }


    /**
     * @return ScreeningRequestRenter[]
     */
    public function getScreeningRequestRenters(): array
    {
        return $this->screeningRequestRenters;
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


    public function setCreatedOn(?Date $val): void
    {
        $this->createdOn = $val;
    }


    public function getCreatedOn(): ?Date
    {
        return $this->createdOn;
    }


    public function setModifiedOn(?Date $val): void
    {
        $this->modifiedOn = $val;
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


    /** @return string[] */
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


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $request = new self(
            $data['landlordId'],
            $data['propertyId'],
            $data['initialBundleId'],
            $data['createdOn'] ? new Date(substr($data['createdOn'], 0, 10)) : null,
            $data['modifiedOn'] ? new Date(substr($data['modifiedOn'], 0, 10)) : null,
            $data['propertyName'] ?? null,
            $data['propertySummaryAddress'] ?? null,
        );

        $request->setScreeningRequestId($data['screeningRequestId'] ?? null);

        foreach ($data['screeningRequestRenters'] as $renterInfo) {
            $request->addScreeningRequestRenter(ScreeningRequestRenter::fromArray($renterInfo));
        }

        return $request;
    }
}
