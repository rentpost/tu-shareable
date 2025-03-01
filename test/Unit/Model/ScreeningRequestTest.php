<?php

declare(strict_types = 1);

namespace Test\Unit\Rentpost\TUShareable\Model;

use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\Model\RenterRole;
use Rentpost\TUShareable\Model\RenterStatus;
use Rentpost\TUShareable\Model\ScreeningRequest;
use Rentpost\TUShareable\Model\ScreeningRequestRenter;

class ScreeningRequestTest extends TestCase
{

    protected function makeRequest(
        int $landlordId,
        int $propertyId,
        int $initialBundleId,
        ?string $propertyName = null,
        ?string $propertySummaryAddress = null,
    ): ScreeningRequest
    {
        return new ScreeningRequest(
            $landlordId,
            $propertyId,
            $initialBundleId,
            new Date('2022-03-10'),
            new Date('2022-04-16'),
            $propertyName,
            $propertySummaryAddress,
        );
    }


    protected function makeRenter(
        int $landlordId,
        int $renterId,
        int $bundleId,
        RenterRole $renterRole,
        ?RenterStatus $renterStatus = null,
        ?string $renterFirstName = null,
        ?string $renterLastName = null,
        ?string $renterMiddleName = null,
    ): ScreeningRequestRenter
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
            15,
        );
    }


    public function testConstructorAndGetters(): void
    {
        $request = $this->makeRequest(1, 2, 3, 'Apartment', 'Street 123');
        $renter = $this->makeRenter(
            4,
            5,
            6,
            RenterRole::Applicant,
            RenterStatus::IdentityVerificationPending,
            'First',
            'Last',
            'Middle',
        );

        $request->addScreeningRequestRenter($renter);

        $request->setScreeningRequestId(123);

        $this->assertInstanceOf(Date::class, $request->getCreatedOn());
        $this->assertInstanceOf(Date::class, $request->getModifiedOn());

        $this->assertSame(123, $request->getScreeningRequestId());
        $this->assertSame(1, $request->getLandlordId());
        $this->assertSame(2, $request->getPropertyId());
        $this->assertSame(3, $request->getInitialBundleId());
        $this->assertSame('2022-03-10', $request->getCreatedOn()->getValue());
        $this->assertSame('2022-04-16', $request->getModifiedOn()->getValue());
        $this->assertSame('Apartment', $request->getPropertyName());
        $this->assertSame('Street 123', $request->getPropertySummaryAddress());

        $this->assertSame($renter, $request->getScreeningRequestRenters()[0]);

        $this->assertSame([
            'screeningRequestId' => 123,
            'landlordId' => 1,
            'propertyId' => 2,
            'initialBundleId' => 3,
            'createdOn' => '2022-03-10',
            'modifiedOn' => '2022-04-16',
            'propertyName' => 'Apartment',
            'propertySummaryAddress' => 'Street 123',
            'screeningRequestRenters' => [
                [
                    'landlordId' => 4,
                    'renterId' => 5,
                    'bundleId' => 6,
                    'renterRole' => 'Applicant',
                    'renterStatus' => 'IdentityVerificationPending',
                    'createdOn' => '2022-03-10',
                    'modifiedOn' => '2022-04-16',
                    'renterFirstName' => 'First',
                    'renterLastName' => 'Last',
                    'renterMiddleName' => 'Middle',
                    'reportsExpireNumberOfDays' => 15,
                ],
            ],
        ], $request->toArray());
    }
}
