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
use Rentpost\TUShareable\Model\Date;
use Rentpost\TUShareable\Model\Email;
use Rentpost\TUShareable\Model\Exam;
use Rentpost\TUShareable\Model\ExamAnswer;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Money;
use Rentpost\TUShareable\Model\Person;
use Rentpost\TUShareable\Model\Phone;
use Rentpost\TUShareable\Model\Property;
use Rentpost\TUShareable\Model\Renter;
use Rentpost\TUShareable\Model\ScreeningRequest;
use Rentpost\TUShareable\Model\ScreeningRequestRenter;
use Rentpost\TUShareable\Model\SocialSecurityNumber;
use Rentpost\TUShareable\ReportType;
use Rentpost\TUShareable\RequestedProduct;

class IntegrationTest extends TestCase
{

    private static ClientInterface $client;


    public static function setUpBeforeClass(): void
    {
        $logger = new Logger('TUShareable');
        $logger->pushHandler(new TestHandler);

        $requestFactory = new HttpFactory;
        $httpClient = new HttpClient;

        $config = parse_ini_file(__DIR__ . '/config');

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


    #[Depends('testCreateLandlord')]
    #[Depends('testCreateProperty')]
    public function testCreateScreeningRequest(Landlord $landlord, Property $property): ScreeningRequest
    {
        $request = new ScreeningRequest(
            $landlord->getLandlordId(),
            $property->getPropertyId(),
            1_004, // bundleId
            null,
            null,
            'Apartment 667',
            'Sacramento, Los Angeles, CA',
        );

        self::$client->createScreeningRequest($request);

        $this->assertIsInt($request->getScreeningRequestId());

        return $request;
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
            'Employed',
            null,
        );

        self::$client->createRenter($renter);

        $this->assertIsInt($renter->getRenterId());

        return $renter;
    }


    #[Depends('testCreateLandlord')]
    #[Depends('testCreateScreeningRequest')]
    #[Depends('testCreateRenter')]
    public function testAddRenterToScreeningRequest(
        Landlord $landlord,
        ScreeningRequest $request,
        Renter $renter,
    ): ScreeningRequestRenter
    {
        $screeningRequestRenter = new ScreeningRequestRenter(
            $landlord->getLandlordId(),
            $renter->getRenterId(),
            3, // bundleId
            'Applicant',
            null,
            null,
            null,
            'Bonnie',
            'Adams',
        );

        self::$client->addRenterToScreeningRequest($request->getScreeningRequestId(), $screeningRequestRenter);

        $this->assertIsInt($screeningRequestRenter->getScreeningRequestRenterId());

        return $screeningRequestRenter;
    }


    #[Depends('testCreateRenter')]
    #[Depends('testAddRenterToScreeningRequest')]
    public function testCreateExam(Renter $renter, ScreeningRequestRenter $screeningRequestRenter): Exam
    {
        $exam = self::$client->createExam($screeningRequestRenter->getScreeningRequestRenterId(), $renter);

        $this->assertIsInt($exam->getExamId());
        $this->assertSame('Questioned', $exam->getResult());

        return $exam;
    }


    #[Depends('testAddRenterToScreeningRequest')]
    #[Depends('testCreateExam')]
    public function testAnswerExam(ScreeningRequestRenter $screeningRequestRenter, Exam $exam): Exam
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
        );

        // Important: Make sure the test was passed
        $this->assertSame('Passed', $newExam->getResult());

        return $newExam;
    }


    #[Depends('testCreateRenter')]
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


    #[Depends('testCreateRenter')]
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

        while ((time() - $reportRequestTime) < 30) {
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

        while ((time() - $reportRequestTime) < 30) {
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
