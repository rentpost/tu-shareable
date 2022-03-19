<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$landlordId = 273210;

$properties = $client->getProperties($landlordId);

print_r($properties);
