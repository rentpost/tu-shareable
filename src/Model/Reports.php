<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable\Model;

/**
 * Class that represents a collection of reports.
 */
class Reports
{

    /** @var Report[] */
    private array $reports = [];


    public function __construct(private int $reportsExpireNumberOfDays)
    {}


    public function addReport(Report $report): self
    {
        $this->reports[] = $report;

        return $this;
    }


    /** @return Report[] */
    public function getReports(): array
    {
        return $this->reports;
    }


    public function getReportsExpireNumberOfDays(): int
    {
        return $this->reportsExpireNumberOfDays;
    }


    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $reports = new self($data['reportsExpireNumberOfDays']);

        foreach ($data['reportResponseModelDetails'] as $reportInfo) {
            $reports->addReport(new Report(
                $reportInfo['providerName'],
                $reportInfo['reportData'],
            ));
        }

        return $reports;
    }
}
