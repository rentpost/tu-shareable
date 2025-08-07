<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

use ArchTech\Enums\From;

enum EmploymentStatus: string
{
    use From;

    case NotEmployed = 'NotEmployed';
    case Employed = 'Employed';
    case SelfEmployed = 'SelfEmployed';
    case Student = 'Student';
}
