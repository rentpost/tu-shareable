<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactory;
use Psr\Log\LoggerInterface;
use Rentpost\TUShareable\Model\Attestation;
use Rentpost\TUShareable\Model\Bundle;
use Rentpost\TUShareable\Model\CultureCode;
use Rentpost\TUShareable\Model\Exam;
use Rentpost\TUShareable\Model\ExamAnswer;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Property;
use Rentpost\TUShareable\Model\Renter;
use Rentpost\TUShareable\Model\Reports;
use Rentpost\TUShareable\Model\ReportType;
use Rentpost\TUShareable\Model\RequestedProduct;
use Rentpost\TUShareable\Model\ScreeningRequest;
use Rentpost\TUShareable\Model\ScreeningRequestRenter;

/**
 * Client library for TransUnion - ShareAble for Rentals API.
 *
 * @author Pekka Laiho <pekka.i.laiho@gmail.com>
 * @author Jacob Thomason <jacob@rentpost.com>
 */
class Client implements ClientInterface
{

    use JsonHelper;


    protected ?string $authToken = null;
    protected ?string $mfaToken = null;


    public function __construct(
        protected readonly LoggerInterface $logger,
        protected readonly HttpRequestFactory $requestFactory,
        protected readonly HttpClient $httpClient,
        protected readonly string $baseUrl,
        protected readonly string $clientId,
        protected readonly string $apiKeyOne,
        protected readonly string $apiKeyTwo,
    ) {}


    /**
     * Fetches a new token from Transunion from our keys (auth and MFA)
     */
    protected function fetchTokens(): void
    {
        $data = ['clientId' => $this->clientId, 'apiKey' => $this->apiKeyOne];
        $response = $this->requestJson('POST', 'Tokens', $data, [], false);
        $responseData = $this->decodeJson($response);
        $this->authToken = $responseData['token'];

        $data = ['clientId' => $this->clientId, 'apiKey' => $this->apiKeyTwo];
        $response = $this->requestJson('POST', 'Tokens', $data, [], false);
        $responseData = $this->decodeJson($response);
        $this->mfaToken = $responseData['token'];
    }


    /**
     * @param string[] $data
     * @param string[] $headers
     */
    protected function requestJson(
        string $method,
        string $resource,
        array $data = [],
        array $headers = [],
        bool $fetchTokens = true,
    ): string
    {
        $headers['Content-Type'] = 'application/json';

        $json = $this->encodeJson($data);

        return $this->request(
            $method,
            $resource,
            $json,
            $headers,
            $fetchTokens,
        );
    }


    /**
     * @param string[] $headers
     */
    protected function request(
        string $method,
        string $resource,
        ?string $data = null,
        array $headers = [],
        bool $fetchTokens = true,
    ): string
    {
        // Fetch auth token
        if ((!$this->authToken || !$this->mfaToken) && $fetchTokens) {
            $this->fetchTokens();
        }

        // Include auth token in headers
        if ($this->authToken && $this->mfaToken) {
            $headers['Authorization'] = $this->authToken;
            $headers['MFAAuthorized'] = $this->mfaToken;
        }

        $url = $this->baseUrl . $resource;
        $request = $this->requestFactory->createRequest($method, $url);

        foreach ($headers as $key => $value) {
            $request = $request->withHeader($key, $value);
        }

        if ($data) {
            $request->getBody()->write($data);
        }

        // Log request
        $this->logger->debug('Request', [
            'method' => $method,
            'url' => $url,
            'headers' => $headers,
            'data' => $data,
        ]);

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (\Throwable $ex) {
            // Throw our own exception instead
            throw new ClientException($ex->getMessage(), $ex->getCode(), $ex);
        }

        $status = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        $this->logger->debug('Response', [
            'status' => $status,
            'body' => $body,
        ]);

        if ($status >= 200 && $status <= 299) {
            return $body;
        }

        throw new ClientException("Received status $status: $body");
    }


    /**
     * Registers the partner with TransUnion
     * This is only required to be called once
     */
    public function register(): void
    {
        $this->requestJson('PUT', 'ApiRegistration/Register');
    }


    /**
     * @return string[]
     */
    public function getStatus(): array
    {
        $response = $this->request('GET', 'System');

        return $this->decodeJson($response);
    }


    /**
     * @return Bundle[]
     */
    public function getBundles(): array
    {
        $response = $this->request('GET', 'Bundles');
        $rows = $this->decodeJson($response);

        $list = [];

        foreach ($rows as $row) {
            $list[] = Bundle::fromArray($row);
        }

        return $list;
    }


    /*
     * Landlords
     */


    public function getLandlord(int $landlordId): Landlord
    {
        $response = $this->request('GET', "Landlords/$landlordId");

        $data = $this->decodeJson($response);

        return Landlord::fromArray($data);
    }


    public function createLandlord(Landlord $landlord): void
    {
        $response = $this->requestJson('POST', 'Landlords', $landlord->toArray());

        $responseData = $this->decodeJson($response);

        $landlord->setLandlordId($responseData['landlordId']);
    }


    public function updateLandlord(Landlord $landlord): void
    {
        $this->requestJson('PUT', "Landlords/{$landlord->getLandlordId()}", $landlord->toArray());
    }


    /*
     * Properties
     */


    public function getProperty(int $landlordId, int $propertyId): Property
    {
        $response = $this->request('GET', "Landlords/$landlordId/Properties/$propertyId");

        $data = $this->decodeJson($response);

        return Property::fromArray($data);
    }


    /**
     * @return Property[]
     */
    public function getProperties(int $landlordId, int $pageNumber = 1, int $pageSize = 10): array
    {
        $params = http_build_query([
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ]);

        $response = $this->request('GET', "Landlords/$landlordId/Properties?" . $params);

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data as $p) {
            $results[] = Property::fromArray($p);
        }

        return $results;
    }


    public function createProperty(int $landlordId, Property $property): void
    {
        $response = $this->requestJson('POST', "Landlords/$landlordId/Properties", $property->toArray());

        $responseData = $this->decodeJson($response);

        $property->setPropertyId($responseData['propertyId']);
    }


    public function updateProperty(int $landlordId, Property $property): void
    {
        $this->requestJson(
            'PUT',
            "Landlords/$landlordId/Properties/{$property->getPropertyId()}",
            $property->toArray(),
        );
    }


    /*
     * Renters
     */


    public function getRenter(int $renterId): Renter
    {
        $response = $this->request('GET', "Renters/$renterId");

        $data = $this->decodeJson($response);

        return Renter::fromArray($data);
    }


    public function createRenter(Renter $renter): void
    {
        $response = $this->requestJson('POST', 'Renters', $renter->toArray());

        $responseData = $this->decodeJson($response);

        $renter->setRenterId($responseData['renterId']);
    }


    public function updateRenter(Renter $renter): void
    {
        $this->requestJson('PUT', "Renters/{$renter->getRenterId()}", $renter->toArray());
    }


    /*
     * ScreeningRequests
     */


    public function getScreeningRequest(int $screeningRequestId): ScreeningRequest
    {
        $response = $this->request('GET', "ScreeningRequests/$screeningRequestId");

        $data = $this->decodeJson($response);

        return ScreeningRequest::fromArray($data);
    }


    /**
     * @return ScreeningRequest[]
     */
    public function getScreeningRequestsForLandlord(
        int $landlordId,
        int $pageNumber = 1,
        int $pageSize = 10,
    ): array
    {
        $params = http_build_query([
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ]);

        $response = $this->request('GET', "Landlords/$landlordId/ScreeningRequests?" . $params);

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data as $sr) {
            $results[] = ScreeningRequest::fromArray($sr);
        }

        return $results;
    }


    /**
     * @return ScreeningRequest[]
     */
    public function getScreeningRequestsForRenter(
        int $renterId,
        int $pageNumber = 1,
        int $pageSize = 10,
    ): array
    {
        $params = http_build_query([
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ]);

        $response = $this->request('GET', "Renters/$renterId/ScreeningRequests?" . $params);

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data as $sr) {
            $results[] = ScreeningRequest::fromArray($sr);
        }

        return $results;
    }


    public function createScreeningRequest(
        int $landlordId,
        int $propertyId,
        ScreeningRequest $request,
    ): void
    {
        $response = $this->requestJson(
            'POST',
            "Landlords/{$landlordId}/Properties/{$propertyId}/ScreeningRequests",
            $request->toArray(),
        );

        $responseData = $this->decodeJson($response);

        $request->setScreeningRequestId($responseData['screeningRequestId']);
    }


    /*
     * ScreeningRequestRenters
     */


    public function getScreeningRequestRenter(int $screeningRequestRenterId): ScreeningRequestRenter
    {
        $response = $this->request('GET', "ScreeningRequestRenters/$screeningRequestRenterId");

        $responseData = $this->decodeJson($response);

        return ScreeningRequestRenter::fromArray($responseData);
    }


    /**
     * @return ScreeningRequestRenter[]
     */
    public function getRentersForScreeningRequest(int $screeningRequestId): array
    {
        $response = $this->request(
            'GET',
            "ScreeningRequests/$screeningRequestId/ScreeningRequestRenters",
        );

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data as $sr) {
            $results[] = ScreeningRequestRenter::fromArray($sr);
        }

        return $results;
    }


    public function addRenterToScreeningRequest(
        int $screeningRequestId,
        ScreeningRequestRenter $screeningRequestRenter,
    ): ScreeningRequestRenter
    {
        $response = $this->requestJson(
            'POST',
            "ScreeningRequests/$screeningRequestId/Renters/{$screeningRequestRenter->getRenterId()}/ScreeningRequestRenters",
            $screeningRequestRenter->toArray(), // No clue on this - docs don't say it's needed, but it is
        );

        $responseData = $this->decodeJson($response);

        $screeningRequestRenter->setScreeningRequestRenterId($responseData['screeningRequestRenterId']);

        return $screeningRequestRenter;
    }


    public function cancelScreeningRequestForRenter(int $screeningRequestRenterId): void
    {
        $this->request('PUT', "ScreeningRequestRenters/$screeningRequestRenterId/Cancel");
    }


    public function validateRenterForScreeningRequest(
        int $screeningRequestRenterId,
        Renter $renter,
    ): string
    {
        $response = $this->requestJson(
            'POST',
            "ScreeningRequestRenters/$screeningRequestRenterId/Validate",
            $renter->getPerson()->toArray(),
        );

        $responseData = $this->decodeJson($response);

        return $responseData['status'];
    }


    /*
     * Attestations
     */


    /** @return Attestation[] */
    public function getAttestationsForProperty(int $landlordId, int $propertyId): array
    {
        $response = $this->requestJson(
            'POST',
            "Landlords/{$landlordId}/Properties/{$propertyId}/Attestations",
        );

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data['attestations'] as $attestation) {
            $results[] = Attestation::fromArray($attestation);
        }

        return $results;
    }


     /** @return Attestation[] */
    public function getAttestationsForRenter(int $renterId, int $screeningRequestId): array
    {
        $response = $this->requestJson(
            'POST',
            "Renters/{$renterId}/ScreeningRequest/{$screeningRequestId}/Attestations",
        );

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data['attestations'] as $attestation) {
            $results[] = Attestation::fromArray($attestation);
        }

        return $results;
    }


    /*
     * Exams and Answers
     */


    public function createExam(
        int $screeningRequestRenterId,
        Renter $renter,
        CultureCode $cultureCode,
    ): Exam
    {
        $requestData = $renter->getPerson()->toArray();

        $requestData['cultureCode'] = $cultureCode->value;

        $response = $this->requestJson(
            'POST',
            "ScreeningRequestRenters/{$screeningRequestRenterId}/Exams",
            $requestData,
        );

        $responseData = $this->decodeJson($response);

        return Exam::fromArray($responseData);
    }


    public function answerExam(
        int $screeningRequestRenterId,
        int $examId,
        ExamAnswer $answer,
        CultureCode $cultureCode,
    ): Exam
    {
        $requestData = $answer->toArray();

        $requestData['cultureCode'] = $cultureCode->value;

        $response = $this->requestJson(
            'POST',
            "ScreeningRequestRenters/$screeningRequestRenterId/Exams/$examId/Answers",
            $requestData,
        );

        $responseData = $this->decodeJson($response);

        return Exam::fromArray($responseData);
    }


    /*
     * Reports
     */


    public function createReport(int $screeningRequestRenterId, Renter $renter): void
    {
        $this->requestJson(
            'POST',
            "Renters/ScreeningRequestRenters/$screeningRequestRenterId/Reports",
            $renter->getPerson()->toArray(),
        );
    }


    /**
     * Gets a list of Products available
     *
     * @return RequestedProduct[]
     */
    public function getReportsAvailableForLandlord(
        int $screeningRequestRenterId,
    ): array
    {
        $response = $this->request(
            'GET',
            "Landlords/ScreeningRequestRenters/$screeningRequestRenterId/Reports/Names",
        );

        $results = [];

        foreach ($this->decodeJson($response) as $productName) {
            $results[] = (new \ReflectionEnum(RequestedProduct::class))->getCase($productName)->getValue();
        }

        return $results;
    }


    public function getReportsForLandlord(
        int $screeningRequestRenterId,
        RequestedProduct $requestedProduct,
        ReportType $reportType,
    ): Reports
    {
        $params = http_build_query([
            'requestedProduct' => $requestedProduct->name,
            'reportType' => $reportType->value,
        ]);

        $response = $this->request(
            'GET',
            "Landlords/ScreeningRequestRenters/$screeningRequestRenterId/Reports?" . $params,
        );

        $responseData = $this->decodeJson($response);

        return Reports::fromArray($responseData);
    }


    /**
     * Gets a list of Products available
     *
     * @return RequestedProduct[]
     */
    public function getReportsAvailableForRenter(
        int $screeningRequestRenterId,
    ): array
    {
        $response = $this->request(
            'GET',
            "Renters/ScreeningRequestRenters/$screeningRequestRenterId/Reports/Names",
        );

        $results = [];

        foreach ($this->decodeJson($response) as $productName) {
            $results[] = (new \ReflectionEnum(RequestedProduct::class))->getCase($productName)->getValue();
        }

        return $results;
    }


    public function getReportsForRenter(
        int $screeningRequestRenterId,
        RequestedProduct $requestedProduct,
        ReportType $reportType,
    ): Reports
    {
        // ShareAble documentation:
        // The Identity Check Report (IdReport) is not available as a renter report
        // and should not be shown to the rental applicant.
        if ($requestedProduct === RequestedProduct::IdReport) {
            throw new ClientException('The IdReport is not available for renter');
        }

        $params = http_build_query([
            'requestedProduct' => $requestedProduct->name,
            'reportType' => $reportType->value,
        ]);

        $response = $this->request(
            'GET',
            "Renters/ScreeningRequestRenters/$screeningRequestRenterId/Reports?" . $params,
        );

        $responseData = $this->decodeJson($response);

        return Reports::fromArray($responseData);
    }
}
