<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

enum RequestedProduct: string
{
    case All = 'all';
    case None = 'none';

    case CanadaCredit = 'canada-credit';
    case CanadaModelCredit = 'canada-model-credit';
    case Credit = 'credit';
    case Criminal = 'criminal';
    case Eviction = 'eviction';
    case IdReport = 'id-report';
    case IncomeInsights = 'income-insights';
    case ResidentScore = 'resident-score';
}
