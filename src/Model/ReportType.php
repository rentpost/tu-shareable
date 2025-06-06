<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

enum ReportType: string
{
    case Html = 'html';
    case Json = 'json';
}
