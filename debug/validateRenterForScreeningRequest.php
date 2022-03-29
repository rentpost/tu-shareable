<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$renter = $client->getRenter(277846);

$status = $client->validateRenterForScreeningRequest(130732, $renter);

echo "$status\n";
