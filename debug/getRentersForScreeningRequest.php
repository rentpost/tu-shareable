<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = 243280;
$renters = $client->getRentersForScreeningRequest($id);

print_r($renters);
