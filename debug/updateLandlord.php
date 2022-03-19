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

$landlord = new Rentpost\TUShareable\Model\Landlord(
    new Rentpost\TUShareable\Model\Email('test@example.com'),
    $newFirstName,
    'Last',
    new Rentpost\TUShareable\Model\Phone('0123456789', 'Home'),
    null,
    new Rentpost\TUShareable\Model\Address('Street', 'Apartment', '', '', 'Los Angeles', 'CA', '12345'),
    true
);

$id = 273210;
$landlord->setLandlordId($id);

$client->updateLandlord($landlord);

// Fetch again to make sure it changed
$landlord = $client->getLandlord($id);
print_r($landlord);
