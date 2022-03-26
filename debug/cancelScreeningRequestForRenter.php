<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$id = 130385;
$client->cancelScreeningRequestForRenter($id);
