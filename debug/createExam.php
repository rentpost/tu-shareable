<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$renter = $client->getRenter(273235);

// Date of Birth is required
$renter->getPerson()->setDateOfBirth(new Rentpost\TUShareable\Model\Date('1990-10-15'));

$exam = $client->createExam(130400, $renter);

print_r($exam);
