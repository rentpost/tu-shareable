<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class that represents a renter associated with a screening request.
 */
class ScreeningRequestRenter
{

    use Validate;


    private ?int $screeningRequestRenterId = null;


    public function __construct(
        #[Assert\NotBlank]
        private int $landlordId,

        #[Assert\NotBlank]
        private int $renterId,

        #[Assert\NotBlank]
        private int $bundleId,

        private RenterRole $renterRole,

        private ?RenterStatus $renterStatus = null,
        private ?Date $createdOn = null,
        private ?Date $modifiedOn = null,
        private ?string $renterFirstName = null,
        private ?string $renterLastName = null,
        private ?string $renterMiddleName = null,
        private ?int $reportsExpireNumberOfDays = null,
    ) {
        $this->validate();
    }


    public function setScreeningRequestRenterId(?int $val): void
    {
        $this->screeningRequestRenterId = $val;
    }


    public function getScreeningRequestRenterId(): ?int
    {
        return $this->screeningRequestRenterId;
    }


    public function getLandlordId(): int
    {
        return $this->landlordId;
    }


    public function getRenterId(): int
    {
        return $this->renterId;
    }


    public function getBundleId(): int
    {
        return $this->bundleId;
    }


    public function setRenterRole(RenterRole $val): void
    {
        $this->renterRole = $val;
    }


    public function getRenterRole(): RenterRole
    {
        return $this->renterRole;
    }


    public function setRenterStatus(?RenterStatus $val): void
    {
        $this->renterStatus = $val;
    }


    public function getRenterStatus(): ?RenterStatus
    {
        return $this->renterStatus;
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


    public function setRenterFirstName(?string $val): void
    {
        $this->renterFirstName = $val;
    }


    public function getRenterFirstName(): ?string
    {
        return $this->renterFirstName;
    }


    public function setRenterLastName(?string $val): void
    {
        $this->renterLastName = $val;
    }


    public function getRenterLastName(): ?string
    {
        return $this->renterLastName;
    }


    public function setRenterMiddleName(?string $val): void
    {
        $this->renterMiddleName = $val;
    }


    public function getRenterMiddleName(): ?string
    {
        return $this->renterMiddleName;
    }


    public function setReportsExpireNumberOfDays(?int $val): void
    {
        $this->reportsExpireNumberOfDays = $val;
    }


    public function getReportsExpireNumberOfDays(): ?int
    {
        return $this->reportsExpireNumberOfDays;
    }


    /** @return string[] */
    public function toArray(): array
    {
        $array = [];

        if ($this->screeningRequestRenterId) {
            $array['screeningRequestRenterId'] = $this->screeningRequestRenterId;
        }

        $array['landlordId'] = $this->landlordId;
        $array['renterId'] = $this->renterId;
        $array['bundleId'] = $this->bundleId;
        $array['renterRole'] = $this->renterRole->value;

        if ($this->renterStatus) {
            $array['renterStatus'] = $this->renterStatus->value;
        }

        if ($this->createdOn) {
            $array['createdOn'] = $this->createdOn->getValue();
        }

        if ($this->modifiedOn) {
            $array['modifiedOn'] = $this->modifiedOn->getValue();
        }

        if ($this->renterFirstName) {
            $array['renterFirstName'] = $this->renterFirstName;
        }

        if ($this->renterLastName) {
            $array['renterLastName'] = $this->renterLastName;
        }

        if ($this->renterMiddleName) {
            $array['renterMiddleName'] = $this->renterMiddleName;
        }

        if ($this->reportsExpireNumberOfDays) {
            $array['reportsExpireNumberOfDays'] = $this->reportsExpireNumberOfDays;
        }

        return $array;
    }


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $renter = new self(
            $data['landlordId'],
            $data['renterId'],
            $data['bundleId'],
            RenterRole::from($data['renterRole']),
            RenterStatus::from($data['renterStatus']),
            $data['createdOn'] ? new Date(substr($data['createdOn'], 0, 10)) : null,
            $data['modifiedOn'] ? new Date(substr($data['modifiedOn'], 0, 10)) : null,
            $data['renterFirstName'] ?? null,
            $data['renterLastName'] ?? null,
            $data['renterMiddleName'] ?? null,
            $data['reportsExpireNumberOfDays'] ?? null,
        );

        $renter->setScreeningRequestRenterId($data['screeningRequestRenterId'] ?? null);

        return $renter;
    }
}
