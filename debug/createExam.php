<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$renter = $client->getRenter(277846);

// Date of Birth is required
$renter->getPerson()->setDateOfBirth(new Rentpost\TUShareable\Model\Date('1947-03-06'));

$exam = $client->createExam(130732, $renter);

print_r($exam);
