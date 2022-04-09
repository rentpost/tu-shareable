<?php

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\Model\ScreeningRequestRenter;
use Rentpost\TUShareable\ValidationException;

class ScreeningRequestRenterTest extends TestCase
{

    public function testConstructorAndGetters()
    {
        $renter = $this->makeObject(1, 2, 3, 'Applicant', 'IdentityVerificationPending', 'First', 'Last', 'Middle');

        $this->assertInstanceOf(Date::class, $renter->getCreatedOn());
        $this->assertInstanceOf(Date::class, $renter->getModifiedOn());

        $this->assertSame(1, $renter->getLandlordId());
        $this->assertSame(2, $renter->getRenterId());
        $this->assertSame(3, $renter->getBundleId());
        $this->assertSame('Applicant', $renter->getRenterRole());
        $this->assertSame('IdentityVerificationPending', $renter->getRenterStatus());
        $this->assertSame('2022-03-10', $renter->getCreatedOn()->getValue());
        $this->assertSame('2022-04-16', $renter->getModifiedOn()->getValue());
        $this->assertSame('First', $renter->getRenterFirstName());
        $this->assertSame('Last', $renter->getRenterLastName());
        $this->assertSame('Middle', $renter->getRenterMiddleName());
        $this->assertSame(15, $renter->getReportsExpireNumberOfDays());

        $this->assertSame([
            'landlordId' => 1,
            'renterId' => 2,
            'bundleId' => 3,
            'renterRole' => 'Applicant',
            'renterStatus' => 'IdentityVerificationPending',
            'createdOn' => '2022-03-10',
            'modifiedOn' => '2022-04-16',
            'renterFirstName' => 'First',
            'renterLastName' => 'Last',
            'renterMiddleName' => 'Middle',
            'reportsExpireNumberOfDays' => 15,
        ], $renter->toArray());
    }


    protected function makeObject(int $landlordId, int $renterId, int $bundleId, string $renterRole,
        ?string $renterStatus = null, ?string $renterFirstName = null, ?string $renterLastName = null,
        ?string $renterMiddleName = null): ScreeningRequestRenter
    {
        return new ScreeningRequestRenter(
            $landlordId,
            $renterId,
            $bundleId,
            $renterRole,
            $renterStatus,
            new Date('2022-03-10'),
            new Date('2022-04-16'),
            $renterFirstName,
            $renterLastName,
            $renterMiddleName,
            15
        );
    }
}
