<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = 130385;
$request = $client->getScreeningRequestRenter($id);

print_r($request);
