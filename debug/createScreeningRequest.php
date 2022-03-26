<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$request = new Rentpost\TUShareable\Model\ScreeningRequest(
    273210,
    163338,
    2,
    null,
    null,
    'Apartment 667',
    'Street, Apartment, Los Angeles, CA'
);

$client->createScreeningRequest($request);

echo $request->getScreeningRequestId() . "\n";
