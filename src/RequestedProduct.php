<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

enum RequestedProduct: string
{
    case Credit = 'Credit';
    case Criminal = 'Criminal';
    case Eviction = 'Eviction Proceedings';
    case IdReport = 'ID Report';
}
