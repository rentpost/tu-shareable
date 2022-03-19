<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = '273210';
$landlord = $client->getLandlord($id);

print_r($landlord);
