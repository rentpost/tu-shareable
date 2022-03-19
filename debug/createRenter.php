<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$person = new Rentpost\TUShareable\Model\Person(
    new Rentpost\TUShareable\Model\Email('test@example.com'),
    'First',
    null,
    'Last',
    new Rentpost\TUShareable\Model\Phone('0123456789', 'Home'),
    new Rentpost\TUShareable\Model\SocialSecurityNumber('123456789'),
    new Rentpost\TUShareable\Model\Date('1990-10-15'),
    new Rentpost\TUShareable\Model\Address('Street', 'Apartment', '', '', 'Los Angeles', 'CA', '12345'),
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
