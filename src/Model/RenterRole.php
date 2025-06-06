<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

enum RenterRole: string
{
    case Applicant = 'Applicant';
    case CoSigner = 'CoSigner';
}
