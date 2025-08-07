<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use ArchTech\Enums\From;

enum RenterRole: string
{
    use From;

    case Applicant = 'Applicant';
    case CoSigner = 'CoSigner';
}
