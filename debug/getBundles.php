<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();
$bundles = $client->getBundles();

print_r($bundles);
