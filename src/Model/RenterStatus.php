<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use ArchTech\Enums\From;

enum RenterStatus: string
{
    use From;

    case IdentityVerificationPending = 'IdentityVerificationPending';
    case ScreeningRequestCanceled = 'ScreeningRequestCanceled';
    case ReadyForReportRequest = 'ReadyForReportRequest';
    case PaymentFailure = 'PaymentFailure';
    case ReportsRequested = 'ReportsRequested';
    case ReportsDeliveryInProgress = 'ReportsDeliveryInProgress';
    case ReportsDeliveryFailed = 'ReportsDeliveryFailed';
    case ReportsDeliverySuccess = 'ReportsDeliverySuccess';
    case RetryLimitExceeded = 'RetryLimitExceeded';
    case ScreeningRequestExpired = 'ScreeningRequestExpired';
}
