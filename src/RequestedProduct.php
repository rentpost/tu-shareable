<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

enum RequestedProduct: string
{
    case None = 'none';
    case Credit = 'credit';
    case Criminal = 'criminal';
    case IdReport = 'idReport';
    case Eviction = 'eviction';
    case All = 'all';
}
