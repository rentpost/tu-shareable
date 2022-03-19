<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$landlordId = 273210;
$propertyId = 163338;

$property = $client->getProperty($landlordId, $propertyId);

print_r($property);
