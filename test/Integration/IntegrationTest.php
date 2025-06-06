<?php

declare(strict_types = 1);

namespace Test\Integration\Rentpost\TuShareable;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\HttpFactory;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use Rentpost\TUShareable\Client;
use Rentpost\TUShareable\ClientInterface;
use Rentpost\TUShareable\Model\Address;
use Rentpost\TUShareable\Model\AttestationGroup;
use Rentpost\TUShareable\Model\CultureCode;
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\Model\EmploymentStatus;
use Rentpost\TUShareable\Model\Exam;
use Rentpost\TUShareable\Model\ExamAnswer;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\Model\Person;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\Model\Property;
use Rentpost\TUShareable\Model\Renter;
use Rentpost\TUShareable\Model\RenterRole;
use Rentpost\TUShareable\Model\ReportType;
use Rentpost\TUShareable\Model\RequestedProduct;
use Rentpost\TUShareable\Model\ScreeningRequest;
use Rentpost\TUShareable\Model\ScreeningRequestRenter;
use Rentpost\TUShareable\Model\SocialSecurityNumber;

class IntegrationTest extends TestCase
{

    private static ClientInterface $client;


    public static function setUpBeforeClass(): void
    {
        $logger = new Logger('TUShareable');
        $logger->pushHandler(new TestHandler);

        $requestFactory = new HttpFactory;
        $httpClient = new HttpClient;

        $config = parse_ini_file(__DIR__ . '/../../config');

        self::$client = new Client(
            $logger,
            $requestFactory,
            $httpClient,
            $config['url'],
            $config['clientId'],
            $config['apiKeyOne'],
            $config['apiKeyTwo'],
        );
    }


    public function testGetStatus(): void
    {
        $status = self::$client->getStatus();

        $this->assertIsArray($status);
        $this->assertNotEmpty($status);

        $this->assertArrayHasKey('version', $status);
    }


    public function getBundles(): void
    {
        $bundles = self::$client->getBundles();

        $this->assertIsArray($bundles);
        $this->assertNotEmpty($bundles);

        foreach ($bundles as $bundle) {
            $this->assertIsInt($bundle->getBundleId());
            $this->assertIsString($bundle->getName());
        }
    }


    public function testCreateLandlord(): Landlord
    {
        $landlord = new Landlord(
            new Email('test@example.com'),
            'First',
            'Last',
            new Phone('0123456789', 'Home'),
            null,
            new Address('Street', 'Apartment', '', '', 'Los Angeles', 'CA', '12345'),
            true,
        );

        self::$client->createLandlord($landlord);

        $this->assertIsInt($landlord->getLandlordId());

        return $landlord;
    }


    #[Depends('testCreateLandlord')]
    public function testGetLandlord(Landlord $landlord): Landlord
    {
        $landlord = self::$client->getLandlord($landlord->getLandlordId());

        $this->assertIsInt($landlord->getLandlordId());
        $this->assertSame('test@example.com', $landlord->getEmail()->getValue());
        $this->assertSame('First', $landlord->getFirstName());
        $this->assertSame('Last', $landlord->getLastName());

        return $landlord;
    }


    #[Depends('testGetLandlord')]
    public function testUpdateLandlord(Landlord $landlord): Landlord
    {
        $landlord->setEmail(new Email('jdoe@example.com'));
        $landlord->setFirstName('John');
        $landlord->setLastName('Doe');
        $landlord->setPhone(new Phone('8583337368', 'Mobile'));
        $landlord->getBusinessAddress()->setAddressLine1('1234 Elm St');

        self::$client->updateLandlord($landlord);
        $landlord = self::$client->getLandlord($landlord->getLandlordId());

        $this->assertIsInt($landlord->getLandlordId());
        $this->assertSame('jdoe@example.com', $landlord->getEmail()->getValue());
        $this->assertSame('John', $landlord->getFirstName());
        $this->assertSame('Doe', $landlord->getLastName());
        $this->assertSame('8583337368', $landlord->getPhone()->getNumber());
        $this->assertSame('1234 Elm St', $landlord->getBusinessAddress()->getAddressLine1());

        return $landlord;
    }


    #[Depends('testUpdateLandlord')]
    public function testCreateProperty(Landlord $landlord): Property
    {
        $property = new Property(
            'Apartment',
            new Money('500'),
            new Money('1000'),
            new Address('Street', 'Apartment', '', '', 'Los Angeles', 'CA', '12345'),
            false,
            0,
            30,
        );

        self::$client->createProperty($landlord->getLandlordId(), $property);

        $this->assertIsInt($property->getPropertyId());

        return $property;
    }


    #[Depends('testUpdateLandlord')]
    #[Depends('testCreateProperty')]
    public function testGetProperty(Landlord $landlord, Property $property): Property
    {
        $property = self::$client->getProperty($landlord->getLandlordId(), $property->getPropertyId());

        $this->assertIsInt($property->getPropertyId());
        $this->assertSame('Apartment', $property->getPropertyName());
        $this->assertEquals('500', $property->getRent()->getValue());

        return $property;
    }


    #[Depends('testUpdateLandlord')]
    #[Depends('testGetProperty')]
    public function testUpdateProperty(Landlord $landlord, Property $property): Property
    {
        $property->setRent(new Money('999'));
        $property->setPropertyName('Updated Apartment');
        $property->setDeposit(new Money('888'));
        $property->getAddress()->setAddressLine1('Updated Street');

        self::$client->updateProperty($landlord->getLandlordId(), $property);

        $property = self::$client->getProperty($landlord->getLandlordId(), $property->getPropertyId());

        $this->assertIsInt($property->getPropertyId());
        $this->assertEquals('999', $property->getRent()->getValue());
        $this->assertSame('Updated Apartment', $property->getPropertyName());
        $this->assertEquals('888', $property->getDeposit()->getValue());
        $this->assertSame('Updated Street', $property->getAddress()->getAddressLine1());

        return $property;
    }


    #[Depends('testUpdateLandlord')]
    #[Depends('testUpdateProperty')]
    public function testGetProperties(Landlord $landlord): void
    {
        $properties = self::$client->getProperties($landlord->getLandlordId());

        $this->assertIsArray($properties);
        $this->assertNotEmpty($properties);

        foreach ($properties as $property) {
            $this->assertIsInt($property->getPropertyId());
            $this->assertIsString($property->getPropertyName());
            $this->assertIsString($property->getRent()->getValue());
        }
    }


    #[Depends('testUpdateLandlord')]
    #[Depends('testUpdateProperty')]
    public function testGettingAttestationsForProperty(
        Landlord $landlord,
        Property $property,
    ): AttestationGroup
    {
        $attestationGroup = self::$client->getAttestationsForProperty(
            $landlord->getLandlordId(),
            $property->getPropertyId(),
        );

        $this->assertInstanceOf(AttestationGroup::class, $attestationGroup);

        $this->assertIsInt($attestationGroup->getAttestationGroupId());
        $this->assertIsArray($attestationGroup->getAttestations());
        $this->assertNotEmpty($attestationGroup->getAttestations());

        foreach ($attestationGroup->getAttestations() as $attestation) {
            $this->assertIsInt($attestation->getAttestationId());
            $this->assertIsString($attestation->getName());
            $this->assertIsString($attestation->getLegalText());
            $this->assertIsBool($attestation->isAffirmativeRequired());
            $this->assertIsString($attestation->getAdditionalInformation());
        }

        return $attestationGroup;
    }


    #[Depends('testUpdateLandlord')]
    #[Depends('testUpdateProperty')]
    #[Depends('testGettingAttestationsForProperty')]
    public function testCreateScreeningRequest(
        Landlord $landlord,
        Property $property,
        AttestationGroup $attestationGroup,
    ): ScreeningRequest
    {
        // Must attest to all attestations
        foreach ($attestationGroup->getAttestations() as $attestation) {
            $attestationGroup->addAttestationResponse($attestation->getAttestationId(), true);
        }

        $request = new ScreeningRequest(
            landlordId: $landlord->getLandlordId(),
            propertyId: $property->getPropertyId(),
            initialBundleId: 1_004,
            attestationGroup: $attestationGroup,
        );

        self::$client->createScreeningRequest(
            $request,
        );

        $this->assertIsInt($request->getScreeningRequestId());

        return $request;
    }


    #[Depends('testCreateScreeningRequest')]
    public function testGetScreeningRequest(ScreeningRequest $request): void
    {
        $request = self::$client->getScreeningRequest($request->getScreeningRequestId());

        $this->assertIsInt($request->getScreeningRequestId());
        $this->assertSame('Updated Apartment', $request->getPropertyName()); // from testUpdateProperty
    }


    #[Depends('testCreateScreeningRequest')]
    public function testGetScreeningRequestsForLandlord(ScreeningRequest $screeningRequest): void
    {
        $screeningRequests = self::$client->getScreeningRequestsForLandlord(
            $screeningRequest->getLandlordId(),
        );

        $this->assertIsArray($screeningRequests);
        $this->assertNotEmpty($screeningRequests);

        foreach ($screeningRequests as $request) {
            $this->assertIsInt($request->getScreeningRequestId());
            $this->assertIsString($request->getPropertyName());
        }
    }


    public function testCreateRenter(): Renter
    {
        // Renter information has to match exactly with documentation
        // in order for validation to work and give expected results.

        $person = new Person(
            new Email('bonnie@example.com'),
            'Bonnie',
            null,
            'Adams',
            new Phone('0123456789', 'Home'),
            new SocialSecurityNumber('666603693'),
            new Date('1947-03-06'),
            new Address('5333 Finsbury Ave', '', '', '', 'Sacramento', 'CA', '95841'),
            true,
        );

        $renter = new Renter(
            $person,
            new Money('3000'),
            'PerMonth',
            new Money('15000'),
            'PerYear',
            new Money('90000'),
            EmploymentStatus::Employed,
        );

        self::$client->createRenter($renter);

        $this->assertIsInt($renter->getRenterId());

        return $renter;
    }


    #[Depends('testCreateRenter')]
    public function testGetRenter(Renter $renter): Renter
    {
        $renter = self::$client->getRenter($renter->getRenterId());

        $this->assertIsInt($renter->getRenterId());
        $this->assertSame('Bonnie', $renter->getPerson()->getFirstName());
        $this->assertSame('Adams', $renter->getPerson()->getLastName());
        $this->assertEquals('3000', $renter->getIncome()->getValue());

        return $renter;
    }


    #[Depends('testGetRenter')]
    public function testUpdateRenter(Renter $renter): Renter
    {
        $renter->setIncome(new Money('1234'));
        $renter->setIncomeFrequency('PerMonth');
        $renter->setEmploymentStatus(EmploymentStatus::NotEmployed);
        $renter->getPerson()->setFirstName('John');
        $renter->getPerson()->setLastName('Doe');
        $renter->getPerson()->setEmail(new Email('jdoe@example.com'));
        $renter->getPerson()->setPhone(new Phone('8583337368', 'Mobile'));
        $renter->getPerson()->setHomeAddress(new Address(
            '1234 Elm St',
            '',
            '',
            '',
            'Los Angeles',
            'CA',
            '90001',
        ));

        self::$client->updateRenter($renter);
        $renter = self::$client->getRenter($renter->getRenterId());

        $this->assertIsInt($renter->getRenterId());
        $this->assertSame('John', $renter->getPerson()->getFirstName());
        $this->assertSame('Doe', $renter->getPerson()->getLastName());
        $this->assertSame('jdoe@example.com', $renter->getPerson()->getEmail()->getValue());
        $this->assertEquals(EmploymentStatus::NotEmployed, $renter->getEmploymentStatus());
        $this->assertSame('8583337368', $renter->getPerson()->getPhone()->getNumber());
        $this->assertEquals('1234', $renter->getIncome()->getValue());
        $this->assertSame('PerMonth', $renter->getIncomeFrequency());
        $this->assertEquals(new Address(
            '1234 Elm St',
            '',
            '',
            '',
            'Los Angeles',
            'CA',
            '90001',
        ), $renter->getPerson()->getHomeAddress());
        $this->assertSame('1234 Elm St', $renter->getPerson()->getHomeAddress()->getAddressLine1());
        $this->assertSame('Los Angeles', $renter->getPerson()->getHomeAddress()->getLocality());

        return $renter;
    }


    #[Depends('testUpdateLandlord')]
    #[Depends('testCreateScreeningRequest')]
    #[Depends('testUpdateRenter')]
    public function testAddRenterToScreeningRequest(
        Landlord $landlord,
        ScreeningRequest $request,
        Renter $renter,
    ): ScreeningRequestRenter
    {
        $screeningRequestRenter = new ScreeningRequestRenter(
            $landlord->getLandlordId(),
            $renter->getRenterId(),
            1_004, // bundleId
            RenterRole::Applicant,
            null,
            null,
            null,
            $renter->getPerson()->getFirstName(),
            $renter->getPerson()->getLastName(),
            $renter->getPerson()->getMiddleName(),
        );

        self::$client->addRenterToScreeningRequest($request->getScreeningRequestId(), $screeningRequestRenter);

        $this->assertIsInt($screeningRequestRenter->getScreeningRequestRenterId());

        return $screeningRequestRenter;
    }


    #[Depends('testAddRenterToScreeningRequest')]
    public function testGetScreeningRequestRenter(ScreeningRequestRenter $screeningRequestRenter): void
    {
        $screeningRequestRenter = self::$client->getScreeningRequestRenter(
            $screeningRequestRenter->getScreeningRequestRenterId(),
        );

        $this->assertIsInt($screeningRequestRenter->getScreeningRequestRenterId());
        $this->assertSame('Applicant', $screeningRequestRenter->getRenterRole()->value);
        $this->assertSame('John', $screeningRequestRenter->getRenterFirstName());
        $this->assertSame('Doe', $screeningRequestRenter->getRenterLastName());
    }


    #[Depends('testCreateScreeningRequest')]
    #[Depends('testAddRenterToScreeningRequest')]
    public function testGetRentersForScreeningRequest(ScreeningRequest $screeningRequest): void
    {
        $screeningRequestRenters = self::$client->getRentersForScreeningRequest(
            $screeningRequest->getScreeningRequestId(),
        );

        $this->assertIsArray($screeningRequestRenters);
        $this->assertNotEmpty($screeningRequestRenters);

        foreach ($screeningRequestRenters as $screeningRequestRenter) {
            $this->assertIsInt($screeningRequestRenter->getScreeningRequestRenterId());
            $this->assertIsString($screeningRequestRenter->getRenterFirstName());
            $this->assertIsString($screeningRequestRenter->getRenterLastName());
        }
    }


    #[Depends('testAddRenterToScreeningRequest')]
    public function testGetScreeningRequestsForRenter(ScreeningRequestRenter $screeningRequestRenter): void
    {
        $screeningRequests = self::$client->getScreeningRequestsForRenter(
            $screeningRequestRenter->getRenterId(),
        );

        $this->assertIsArray($screeningRequests);
        $this->assertNotEmpty($screeningRequests);

        foreach ($screeningRequests as $request) {
            $this->assertIsInt($request->getScreeningRequestId());
            $this->assertIsString($request->getPropertyName());
        }
    }


    #[Depends('testAddRenterToScreeningRequest')]
    public function testCancelScreeningRequestForRenter(
        ScreeningRequestRenter $screeningRequestRenter,
    ): void
    {
        self::$client->cancelScreeningRequestForRenter($screeningRequestRenter->getScreeningRequestRenterId());

        // We don't need to assert anything because if the request
        // fails an exception is thrown. But we have to assert something
        // to disable phpunit warning.
        $this->assertTrue(true);
    }


    #[Depends('testUpdateRenter')]
    #[Depends('testAddRenterToScreeningRequest')]
    public function testCreateExam(Renter $renter, ScreeningRequestRenter $screeningRequestRenter): Exam
    {
        $exam = self::$client->createExam(
            $screeningRequestRenter->getScreeningRequestRenterId(),
            $renter,
            CultureCode::fromAddress($renter->getPerson()->getHomeAddress()),
        );

        $this->assertIsInt($exam->getExamId());
        $this->assertSame('Questioned', $exam->getResult());

        return $exam;
    }


    #[Depends('testUpdateRenter')]
    #[Depends('testAddRenterToScreeningRequest')]
    #[Depends('testCreateExam')]
    public function testAnswerExam(
        Renter $renter,
        ScreeningRequestRenter $screeningRequestRenter,
        Exam $exam,
    ): Exam
    {
        $answer = new ExamAnswer;

        foreach ($exam->getAuthenticationQuestions() as $question) {
            $choices = $question->getChoices();
            $firstChoice = reset($choices);
            $answer->addQuestionAnswer($question, $firstChoice);
        }

        $newExam = self::$client->answerExam(
            $screeningRequestRenter->getScreeningRequestRenterId(),
            $exam->getExamId(),
            $answer,
            CultureCode::fromAddress($renter->getPerson()->getHomeAddress()),
        );

        // Important: Make sure the test was passed
        $this->assertSame('Passed', $newExam->getResult());

        return $newExam;
    }


    #[Depends('testUpdateRenter')]
    #[Depends('testAddRenterToScreeningRequest')]
    #[Depends('testAnswerExam')]
    public function testValidateRenterForScreeningRequest(
        Renter $renter,
        ScreeningRequestRenter $screeningRequestRenter,
        Exam $exam,
    ): string
    {
        $status = self::$client->validateRenterForScreeningRequest(
            $screeningRequestRenter->getScreeningRequestRenterId(),
            $renter,
        );

        // Important: Make sure validation passes
        $this->assertSame('Verified', $status);

        return $status;
    }


    #[Depends('testUpdateRenter')]
    #[Depends('testAddRenterToScreeningRequest')]
    #[Depends('testValidateRenterForScreeningRequest')]
    public function testCreateReport(
        Renter $renter,
        ScreeningRequestRenter $screeningRequestRenter,
        string $validationResult,
    ): int
    {
        self::$client->createReport($screeningRequestRenter->getScreeningRequestRenterId(), $renter);

        // We don't need to assert anything because if the request
        // fails an exception is thrown. But we have to assert something
        // to disable phpunit warning.
        $this->assertTrue(true);

        return time();
    }


    #[Depends('testAddRenterToScreeningRequest')]
    #[Depends('testCreateReport')]
    public function testGetReportsForLandlord(
        ScreeningRequestRenter $screeningRequestRenter,
        int $reportRequestTime,
    ): void
    {
        // Reports take some time to finish...
        // Normally we execute this only after receiving a notification from the service
        // But we can just sleep until they should be finished

        while ((time() - $reportRequestTime) < 60) {
            sleep(1);
        }

        $types = [
            RequestedProduct::Credit,
            RequestedProduct::Criminal,
            RequestedProduct::Eviction,
        ];

        foreach ($types as $type) {
            $reports = self::$client->getReportsForLandlord(
                $screeningRequestRenter->getScreeningRequestRenterId(),
                $type,
                ReportType::Html,
            );

            $report = $reports->getReports()[0];

            $this->assertSame(ucfirst($type->name), $report->getProviderName());

            // For now just assert the content is long enough rather than parsing the html
            $this->assertGreaterThan(1_024, strlen($report->getReportData()));
        }
    }


    #[Depends('testAddRenterToScreeningRequest')]
    #[Depends('testCreateReport')]
    public function testGetReportsForRenter(
        ScreeningRequestRenter $screeningRequestRenter,
        int $reportRequestTime,
    ): void
    {
        // Reports take some time to finish...
        // Normally we execute this only after receiving a notification from the service
        // But we can just sleep until they should be finished

        while ((time() - $reportRequestTime) < 60) {
            sleep(1);
        }

        $types = [
            RequestedProduct::Credit,
            RequestedProduct::Criminal,
            RequestedProduct::Eviction,
        ];

        foreach ($types as $type) {
            $reports = self::$client->getReportsForRenter(
                $screeningRequestRenter->getScreeningRequestRenterId(),
                $type,
                ReportType::Html,
            );

            $report = $reports->getReports()[0];

            $this->assertSame(ucfirst($type->name), $report->getProviderName());

            // For now just assert the content is long enough rather than parsing the html
            $this->assertGreaterThan(1_024, strlen($report->getReportData()));
        }
    }
}
