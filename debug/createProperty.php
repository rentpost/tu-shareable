<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$property = new Rentpost\TUShareable\Model\Property(
    'Apartment',
    new Rentpost\TUShareable\Model\Money('500'),
    new Rentpost\TUShareable\Model\Money('1000'),
    new Rentpost\TUShareable\Model\Address('Street', 'Apartment', '', '', 'Los Angeles', 'CA', '12345'),
    false,
    0,
    30
);

$landlordId = 273210;

$client->createProperty($landlordId, $property);

echo $property->getPropertyId() . "\n";
