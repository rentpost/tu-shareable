<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactory;
use Psr\Log\LoggerInterface;
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
 * Client library for TransUnion - ShareAble for Rentals API.
 */
class Client implements ClientInterface
{

    use JsonHelper;


    protected ?string $authToken = null;

    protected ModelFactory $modelFactory;


    public function __construct(
        protected LoggerInterface $logger,
        protected HttpRequestFactory $requestFactory,
        protected HttpClient $httpClient,
        protected string $baseUrl,
        protected string $clientId,
        protected string $apiKey
    ) {
        $this->modelFactory = new ModelFactory;
    }


    protected function fetchToken(): void
    {
        $data = ['clientId' => $this->clientId, 'apiKey' => $this->apiKey];
        $response = $this->requestJson('POST', 'Tokens', $data, [], false);

        $responseData = $this->decodeJson($response);
        $this->authToken = $responseData['token'];
    }


    /**
     * @param string[] $data
     * @param string[] $headers
     */
    protected function requestJson(
        string $method,
        string $resource,
        array $data,
        array $headers = [],
        bool $fetchToken = true
    ): string
    {
        $headers['Content-Type'] = 'application/json';

        $json = $this->encodeJson($data);

        return $this->request(
            $method,
            $resource,
            $json,
            $headers,
            $fetchToken
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
        bool $fetchToken = true
    ): string
    {
        // Fetch auth token
        if (!$this->authToken && $fetchToken) {
            $this->fetchToken();
        }

        // Include auth token in headers
        if ($this->authToken) {
            $headers['Authorization'] = $this->authToken;
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
            $list[] = $this->modelFactory->make(Bundle::class, $row);
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

        return $this->modelFactory->make(Landlord::class, $data);
    }


    public function createLandlord(Landlord $landlord): void
    {
        $response = $this->requestJson('POST', 'Landlords', $landlord->toArray());

        $responseData = $this->decodeJson($response);

        $landlord->setLandlordId($responseData['landlordId']);
    }


    public function updateLandlord(Landlord $landlord): void
    {
        $this->requestJson('PUT', 'Landlords', $landlord->toArray());
    }


    /*
     * Properties
     */


    public function getProperty(int $landlordId, int $propertyId): Property
    {
        $response = $this->request('GET', "Landlords/$landlordId/Properties/$propertyId");

        $data = $this->decodeJson($response);

        return $this->modelFactory->make(Property::class, $data);
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
            $results[] = $this->modelFactory->make(Property::class, $p);
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
        $this->requestJson('PUT', "Landlords/$landlordId/Properties", $property->toArray());
    }


    /*
     * Renters
     */


    public function getRenter(int $renterId): Renter
    {
        $response = $this->request('GET', "Renters/$renterId");

        $data = $this->decodeJson($response);

        return $this->modelFactory->make(Renter::class, $data);
    }


    public function createRenter(Renter $renter): void
    {
        $response = $this->requestJson('POST', 'Renters', $renter->toArray());

        $responseData = $this->decodeJson($response);

        $renter->setRenterId($responseData['renterId']);
    }


    public function updateRenter(Renter $renter): void
    {
        $this->requestJson('PUT', 'Renters', $renter->toArray());
    }


    /*
     * ScreeningRequests
     */


    public function getScreeningRequest(int $screeningRequestId): ScreeningRequest
    {
        $response = $this->request('GET', "ScreeningRequests/$screeningRequestId");

        $data = $this->decodeJson($response);

        return $this->modelFactory->make(ScreeningRequest::class, $data);
    }


    /**
     * @return ScreeningRequest[]
     */
    public function getScreeningRequestsForLandlord(int $landlordId, int $pageNumber = 1, int $pageSize = 10): array
    {
        $params = http_build_query([
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ]);

        $response = $this->request('GET', "Landlords/$landlordId/ScreeningRequests?" . $params);

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data as $sr) {
            $results[] = $this->modelFactory->make(ScreeningRequest::class, $sr);
        }

        return $results;
    }


    /**
     * @return ScreeningRequest[]
     */
    public function getScreeningRequestsForRenter(int $renterId, int $pageNumber = 1, int $pageSize = 10): array
    {
        $params = http_build_query([
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ]);

        $response = $this->request('GET', "Renters/$renterId/ScreeningRequests?" . $params);

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data as $sr) {
            $results[] = $this->modelFactory->make(ScreeningRequest::class, $sr);
        }

        return $results;
    }


    public function createScreeningRequest(ScreeningRequest $request): void
    {
        $response = $this->requestJson('POST', 'ScreeningRequests', $request->toArray());

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

        return $this->modelFactory->make(ScreeningRequestRenter::class, $responseData);
    }


    /**
     * @return ScreeningRequestRenter[]
     */
    public function getRentersForScreeningRequest(int $screeningRequestId): array
    {
        $response = $this->request('GET', "ScreeningRequests/$screeningRequestId/ScreeningRequestRenters");

        $data = $this->decodeJson($response);

        $results = [];

        foreach ($data as $sr) {
            $results[] = $this->modelFactory->make(ScreeningRequestRenter::class, $sr);
        }

        return $results;
    }


    public function addRenterToScreeningRequest(int $screeningRequestId, ScreeningRequestRenter $renter): void
    {
        $response = $this->requestJson(
            'POST',
            "ScreeningRequests/$screeningRequestId/ScreeningRequestRenters",
            $renter->toArray()
        );

        $responseData = $this->decodeJson($response);

        $renter->setScreeningRequestRenterId($responseData['screeningRequestRenterId']);
    }


    public function cancelScreeningRequestForRenter(int $screeningRequestRenterId): void
    {
        $this->request('PUT', "ScreeningRequestRenters/$screeningRequestRenterId/Cancel");
    }


    public function validateRenterForScreeningRequest(int $screeningRequestRenterId, Renter $renter): string
    {
        $response = $this->requestJson(
            'POST',
            "ScreeningRequestRenters/$screeningRequestRenterId/Validate",
            $renter->getPerson()->toArray()
        );

        $responseData = $this->decodeJson($response);

        return $responseData['status'];
    }


    /*
     * Exams and Answers
     */


    public function createExam(
        int $screeningRequestRenterId,
        Renter $renter,
        ?string $externalReferenceNumber = null
    ): Exam
    {
        $requestData = [
            'person' => $renter->getPerson()->toArray(),
        ];

        if ($externalReferenceNumber) {
            $requestData['externalReferenceNumber'] = $externalReferenceNumber;
        }

        $response = $this->requestJson('POST', "ScreeningRequestRenters/$screeningRequestRenterId/Exams", $requestData);

        $responseData = $this->decodeJson($response);

        return $this->modelFactory->make(Exam::class, $responseData);
    }


    public function answerExam(int $screeningRequestRenterId, int $examId, ExamAnswer $answer): Exam
    {
        $response = $this->requestJson(
            'POST',
            "ScreeningRequestRenters/$screeningRequestRenterId/Exams/$examId/Answers",
            $answer->toArray()
        );

        $responseData = $this->decodeJson($response);

        return $this->modelFactory->make(Exam::class, $responseData);
    }


    /*
     * Reports
     */


    public function createReport(int $screeningRequestRenterId, Renter $renter): void
    {
        $requestData = [
            'person' => $renter->getPerson()->toArray(),
        ];

        $this->requestJson('POST', "Renters/ScreeningRequestRenters/$screeningRequestRenterId/Reports", $requestData);
    }


    /**
     * Gets a list of Products available
     *
     * @return string[]
     */
    public function getReportsAvailableForLandlord(
        int $screeningRequestRenterId,
    ): array
    {
        $response = $this->request(
            'GET',
            "Landlords/ScreeningRequestRenters/$screeningRequestRenterId/Reports/Names"
        );

        return $this->decodeJson($response);
    }


    public function getReportsForLandlord(
        int $screeningRequestRenterId,
        RequestedProduct $requestedProduct,
        ReportType $reportType
    ): Reports
    {
        $params = http_build_query([
            'requestedProduct' => $requestedProduct->value,
            'reportType' => $reportType->value,
        ]);

        $response = $this->request(
            'GET',
            "Landlords/ScreeningRequestRenters/$screeningRequestRenterId/Reports?" . $params
        );

        $responseData = $this->decodeJson($response);

        return $this->modelFactory->make(Reports::class, $responseData);
    }


    /**
     * Gets a list of Products available
     *
     * @return string[]
     */
    public function getReportsAvailableForRenter(
        int $screeningRequestRenterId,
    ): array
    {
        $response = $this->request(
            'GET',
            "Renters/ScreeningRequestRenters/$screeningRequestRenterId/Reports/Names"
        );

        return $this->decodeJson($response);
    }


    public function getReportsForRenter(
        int $screeningRequestRenterId,
        RequestedProduct $requestedProduct,
        ReportType $reportType
    ): Reports
    {
        // ShareAble documentation:
        // The Identity Check Report (IdReport) is not available as a renter report
        // and should not be shown to the rental applicant.
        if ($requestedProduct === RequestedProduct::IdReport) {
            throw new ClientException('The IdReport is not available for renter');
        }

        $params = http_build_query([
            'requestedProduct' => $requestedProduct->value,
            'reportType' => $reportType->value,
        ]);

        $response = $this->request(
            'GET',
            "Renters/ScreeningRequestRenters/$screeningRequestRenterId/Reports?" . $params
        );

        $responseData = $this->decodeJson($response);

        return $this->modelFactory->make(Reports::class, $responseData);
    }
}
