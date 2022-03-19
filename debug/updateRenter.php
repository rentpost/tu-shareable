<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

// Select a random names
$names = [
    'John',
    'Jack',
    'Jill',
    'James',
    'Mark',
    'Meta',
    'Mary',
    'Millie',
    'Smith',
    'Sam',
    'Susan',
    'Sara',
];

$newFirstName = $names[mt_rand(0, count($names) - 1)];
echo "Set first name to: $newFirstName\n";

$newIncome = (string)mt_rand(2000, 4000);
echo "Set income to: $newIncome\n";

$person = new Rentpost\TUShareable\Model\Person(
    new Rentpost\TUShareable\Model\Email('test@example.com'),
    $newFirstName,
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
    new Rentpost\TUShareable\Model\Money($newIncome),
    'PerMonth',
    new Rentpost\TUShareable\Model\Money('15000'),
    'PerYear',
    new Rentpost\TUShareable\Model\Money('90000'),
    'Employed',
    null
);

$id = 273235;
$renter->setRenterId($id);

$client->updateRenter($renter);

// Fetch again to make sure it changed
$renter = $client->getRenter($id);
print_r($renter);
