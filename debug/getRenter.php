<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = 273235;
$renter = $client->getRenter($id);

print_r($renter);
