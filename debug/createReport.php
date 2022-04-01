<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$renter = $client->getRenter(277846);

// Date of Birth is required
$renter->getPerson()->setDateOfBirth(new Rentpost\TUShareable\Model\Date('1947-03-06'));

// Social security number is required
$renter->getPerson()->setSocialSecurityNumber(new Rentpost\TUShareable\Model\SocialSecurityNumber('666603693'));

$client->createReport(131384, $renter);
