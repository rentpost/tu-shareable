<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

enum EmploymentStatus: string
{
    case NotEmployed = 'NotEmployed';
    case Employed = 'Employed';
    case SelfEmployed = 'SelfEmployed';
    case Student = 'Student';
}
