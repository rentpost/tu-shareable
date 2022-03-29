<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$person = new Rentpost\TUShareable\Model\Person(
    new Rentpost\TUShareable\Model\Email('bonnie@example.com'),
    'Bonnie',
    null,
    'Adams',
    new Rentpost\TUShareable\Model\Phone('0123456789', 'Home'),
    new Rentpost\TUShareable\Model\SocialSecurityNumber('666603693'),
    new Rentpost\TUShareable\Model\Date('1947-03-06'),
    new Rentpost\TUShareable\Model\Address('5333 Finsbury Ave', '', '', '', 'Sacramento', 'CA', '95841'),
    true
);

$renter = new Rentpost\TUShareable\Model\Renter(
    $person,
    new Rentpost\TUShareable\Model\Money('3000'),
    'PerMonth',
    new Rentpost\TUShareable\Model\Money('15000'),
    'PerYear',
    new Rentpost\TUShareable\Model\Money('90000'),
    'Employed',
    null
);

$client->createRenter($renter);

echo $renter->getRenterId() . "\n";
