<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

class WebhookReportDelivery
{

    public function __construct(protected int $screeningRequestRenterId, protected string $reportsDeliveryStatus)
    {
    }


    public function getScreeningRequestRenterId(): int
    {
        return $this->screeningRequestRenterId;
    }


    public function getReportsDeliveryStatus(): string
    {
        return $this->reportsDeliveryStatus;
    }
}
