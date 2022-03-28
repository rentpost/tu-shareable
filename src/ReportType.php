<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

enum ReportType: string
{
    case Html = 'html';
    case Json = 'json';
}
