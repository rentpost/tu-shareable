<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$renter = $client->getRenter(273235);

// Date of Birth is required
$renter->getPerson()->setDateOfBirth(new Rentpost\TUShareable\Model\Date('1990-10-15'));

// Social security number is required
$renter->getPerson()->setSocialSecurityNumber(new Rentpost\TUShareable\Model\SocialSecurityNumber('123456789'));

$client->createReport(130400, $renter);
