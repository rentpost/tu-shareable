<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = 273235;
$requests = $client->getScreeningRequestsForRenter($id);

print_r($requests);
