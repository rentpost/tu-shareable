<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = 273210;
$requests = $client->getScreeningRequestsForLandlord($id);

print_r($requests);
