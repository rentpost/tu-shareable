<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$status = $client->getStatus();

print_r($status);
