<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$screeningRequestRenter = new Rentpost\TUShareable\Model\ScreeningRequestRenter(
    273210,
    273235,
    2,
    'Applicant',
    null,
    null,
    null,
    'Meta',
    'Last'
);

$client->addRenterToScreeningRequest(243295, $screeningRequestRenter);

echo $screeningRequestRenter->getScreeningRequestRenterId() . "\n";
