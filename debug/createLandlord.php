<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$landlord = new Rentpost\TUShareable\Model\Landlord(
    new Rentpost\TUShareable\Model\Email('test@example.com'),
    'First',
    'Last',
    new Rentpost\TUShareable\Model\Phone('0123456789', 'Home'),
    null,
    new Rentpost\TUShareable\Model\Address('Street', 'Apartment', '', '', 'Los Angeles', 'CA', '12345'),
    true
);

$client->createLandlord($landlord);

echo $landlord->getLandlordId() . "\n";
