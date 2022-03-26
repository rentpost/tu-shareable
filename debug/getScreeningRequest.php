<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = 243295;
$request = $client->getScreeningRequest($id);

print_r($request);
