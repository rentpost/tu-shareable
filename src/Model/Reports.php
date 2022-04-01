<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a collection of reports.
 */
class Reports
{

    /**
     * @var Report[]
     */
    protected array $reports = [];


    public function __construct(protected int $reportsExpireNumberOfDays)
    {
    }


    public function addReport(Report $report): self
    {
        $this->reports[] = $report;

        return $this;
    }


    public function getReportsExpireNumberOfDays(): int
    {
        return $this->reportsExpireNumberOfDays;
    }


    /**
     * @return Report[]
     */
    public function getReports(): array
    {
        return $this->reports;
    }
}
