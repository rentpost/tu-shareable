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


    protected ?int $screeningRequestRenterId = null;

    #[Assert\NotBlank]
    protected int $landlordId;

    #[Assert\NotBlank]
    protected int $renterId;

    #[Assert\NotBlank]
    protected int $bundleId;

    #[Assert\NotBlank]
    #[Assert\Choice(['Applicant', 'CoSigner'])]
    protected string $renterRole;

    // IdentityVerificationPending, ScreeningRequestCanceled, ReadyForReportRequest, PaymentFailure,
    // ReportsDeliveryInProgress, ReportsDeliveryFailed, ReportsDeliverySuccess, RetryLimitExceeded,
    // ScreeningRequestExpired
    protected ?string $renterStatus;

    protected ?Date $createdOn;

    protected ?Date $modifiedOn;

    protected ?string $renterFirstName;

    protected ?string $renterLastName;

    protected ?string $renterMiddleName;

    protected ?int $reportsExpireNumberOfDays;


    public function __construct(
        int $landlordId,
        int $renterId,
        int $bundleId,
        string $renterRole,
        ?string $renterStatus = null,
        ?Date $createdOn = null,
        ?Date $modifiedOn = null,
        ?string $renterFirstName = null,
        ?string $renterLastName = null,
        ?string $renterMiddleName = null,
        ?int $reportsExpireNumberOfDays = null
    ) {
        $this->landlordId = $landlordId;
        $this->renterId = $renterId;
        $this->bundleId = $bundleId;
        $this->renterRole = $renterRole;
        $this->renterStatus = $renterStatus;
        $this->createdOn = $createdOn;
        $this->modifiedOn = $modifiedOn;
        $this->renterFirstName = $renterFirstName;
        $this->renterLastName = $renterLastName;
        $this->renterMiddleName = $renterMiddleName;
        $this->reportsExpireNumberOfDays = $reportsExpireNumberOfDays;

        $this->validate();
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


    public function getRenterRole(): string
    {
        return $this->renterRole;
    }


    public function getRenterStatus(): ?string
    {
        return $this->renterStatus;
    }


    public function getCreatedOn(): ?Date
    {
        return $this->createdOn;
    }


    public function getModifiedOn(): ?Date
    {
        return $this->modifiedOn;
    }


    public function getRenterFirstName(): ?string
    {
        return $this->renterFirstName;
    }


    public function getRenterLastName(): ?string
    {
        return $this->renterLastName;
    }


    public function getRenterMiddleName(): ?string
    {
        return $this->renterMiddleName;
    }


    public function getReportsExpireNumberOfDays(): ?int
    {
        return $this->reportsExpireNumberOfDays;
    }


    public function setScreeningRequestRenterId(?int $val): void
    {
        $this->screeningRequestRenterId = $val;
    }


    public function setRenterStatus(?string $val): void
    {
        $this->renterStatus = $val;
    }


    public function setCreatedOn(?Date $val): void
    {
        $this->createdOn = $val;
    }


    public function setModifiedOn(?Date $val): void
    {
        $this->modifiedOn = $val;
    }


    public function setRenterFirstName(?string $val): void
    {
        $this->renterFirstName = $val;
    }


    public function setRenterLastName(?string $val): void
    {
        $this->renterLastName = $val;
    }


    public function setRenterMiddleName(?string $val): void
    {
        $this->renterMiddleName = $val;
    }


    public function setReportsExpireNumberOfDays(?int $val): void
    {
        $this->reportsExpireNumberOfDays = $val;
    }


    /**
     * @return string[]
     */
    public function toArray(): array
    {
        $array = [];

        if ($this->screeningRequestRenterId) {
            $array['screeningRequestRenterId'] = $this->screeningRequestRenterId;
        }

        $array['landlordId'] = $this->landlordId;
        $array['renterId'] = $this->renterId;
        $array['bundleId'] = $this->bundleId;
        $array['renterRole'] = $this->renterRole;

        if ($this->renterStatus) {
            $array['renterStatus'] = $this->renterStatus;
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
}
