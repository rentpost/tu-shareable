<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$screeningRequestRenter = new Rentpost\TUShareable\Model\ScreeningRequestRenter(
    273210,
    277846,
    3,
    'Applicant',
    null,
    null,
    null,
    'Bonnie',
    'Adams'
);

$client->addRenterToScreeningRequest(244676, $screeningRequestRenter);

echo $screeningRequestRenter->getScreeningRequestRenterId() . "\n";
