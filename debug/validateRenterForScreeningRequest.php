<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$renter = $client->getRenter(273235);

$status = $client->validateRenterForScreeningRequest(130400, $renter);

echo "$status\n";
