<?php
require(__DIR__ . '/bootstrap.php');

$client = getShareableClient();

$newName = "Apartment " . mt_rand(100, 999);
echo "Set name to: $newName\n";

$property = new Rentpost\TUShareable\Model\Property(
    $newName,
    new Rentpost\TUShareable\Model\Money('500'),
    new Rentpost\TUShareable\Model\Money('1000'),
    new Rentpost\TUShareable\Model\Address('Street', 'Apartment', '', '', 'Los Angeles', 'CA', '12345'),
    false,
    0,
    30
);

$landlordId = 273210;
$propertyId = 163338;

$property->setPropertyId($propertyId);

$client->updateProperty($landlordId, $property);

// Fetch again to make sure it changed
$property = $client->getProperty($landlordId, $propertyId);
print_r($property);
