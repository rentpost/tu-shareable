<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Rentpost\TUShareable\Model\Bundle;
use Rentpost\TUShareable\Model\Exam;
use Rentpost\TUShareable\Model\ExamAnswer;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Property;
use Rentpost\TUShareable\Model\Renter;
use Rentpost\TUShareable\Model\Reports;
use Rentpost\TUShareable\Model\ScreeningRequest;
use Rentpost\TUShareable\Model\ScreeningRequestRenter;

/**
 * Interface for TransUnion - ShareAble for Rentals API.
 */
interface ClientInterface
{

    /**
     * @return string[]
     */
    public function getStatus(): array;


    /**
     * @return Bundle[]
     */
    public function getBundles(): array;


    /*
     * Landlords
     */


    public function getLandlord(int $landlordId): Landlord;


    public function createLandlord(Landlord $landlord): void;


    public function updateLandlord(Landlord $landlord): void;


    /*
     * Properties
     */


    public function getProperty(int $landlordId, int $propertyId): Property;


    /**
     * @return Property[]
     */
    public function getProperties(int $landlordId, int $pageNumber = 1, int $pageSize = 10): array;


    public function createProperty(int $landlordId, Property $property): void;


    public function updateProperty(int $landlordId, Property $property): void;


    /*
     * Renters
     */


    public function getRenter(int $renterId): Renter;


    public function createRenter(Renter $renter): void;


    public function updateRenter(Renter $renter): void;


    /*
     * ScreeningRequests
     */


    public function getScreeningRequest(int $screeningRequestId): ScreeningRequest;


    /**
     * @return ScreeningRequest[]
     */
    public function getScreeningRequestsForLandlord(int $landlordId, int $pageNumber = 1, int $pageSize = 10): array;


    /**
     * @return ScreeningRequest[]
     */
    public function getScreeningRequestsForRenter(int $renterId, int $pageNumber = 1, int $pageSize = 10): array;


    public function createScreeningRequest(ScreeningRequest $request): void;


    /*
     * ScreeningRequestRenters
     */


    public function getScreeningRequestRenter(int $screeningRequestRenterId): ScreeningRequestRenter;


    /**
     * @return ScreeningRequestRenter[]
     */
    public function getRentersForScreeningRequest(int $screeningRequestId): array;


    public function addRenterToScreeningRequest(int $screeningRequestId, ScreeningRequestRenter $renter): void;


    public function cancelScreeningRequestForRenter(int $screeningRequestRenterId): void;


    public function validateRenterForScreeningRequest(int $screeningRequestRenterId, Renter $renter): string;


    /*
     * Exams and Answers
     */


    public function createExam(
        int $screeningRequestRenterId,
        Renter $renter,
        ?string $externalReferenceNumber = null
    ): Exam;


    public function answerExam(int $screeningRequestRenterId, int $examId, ExamAnswer $answer): Exam;


    /*
     * Reports
     */


    public function createReport(int $screeningRequestRenterId, Renter $renter): void;


    public function getReportsForLandlord(
        int $screeningRequestRenterId,
        RequestedProduct $requestedProduct,
        ReportType $reportType
    ): Reports;


    public function getReportsForRenter(
        int $screeningRequestRenterId,
        RequestedProduct $requestedProduct,
        ReportType $reportType
    ): Reports;
}
