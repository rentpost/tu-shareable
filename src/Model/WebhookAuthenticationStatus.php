<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

class WebhookAuthenticationStatus
{

    public function __construct(
        private int $screeningRequestRenterId,
        private string $manualAuthenticationStatus,
    ) {}


    public function getScreeningRequestRenterId(): int
    {
        return $this->screeningRequestRenterId;
    }


    public function getManualAuthenticationStatus(): string
    {
        return $this->manualAuthenticationStatus;
    }
}
