<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$reports = $client->getReportsForRenter(
    130732,
    Rentpost\TUShareable\RequestedProduct::Criminal,
    Rentpost\TUShareable\ReportType::Html
);

$report = $reports->getReports()[0];

$header = "<!doctype html>\n";

$file = __DIR__ . '/renter_report.html';

file_put_contents($file, $header . $report->getReportData());

echo "Saved as: $file\n";
