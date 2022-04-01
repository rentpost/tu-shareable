<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a report.
 */
class Report
{

    public function __construct(
        protected string $providerName,
        protected string $reportData
    )
    {
    }


    public function getProviderName(): string
    {
        return $this->providerName;
    }


    public function getReportData(): string
    {
        return $this->reportData;
    }
}
