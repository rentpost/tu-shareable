<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$request = new Rentpost\TUShareable\Model\ScreeningRequest(
    273210,
    163338,
    3,
    null,
    null,
    'Apartment 667',
    'Sacramento, Los Angeles, CA'
);

$client->createScreeningRequest($request);

echo $request->getScreeningRequestId() . "\n";
