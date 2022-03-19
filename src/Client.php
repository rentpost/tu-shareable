<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactory;
use Psr\Log\LoggerInterface;
use Rentpost\TUShareable\Model\Bundle;
use Rentpost\TUShareable\Model\Landlord;
use Rentpost\TUShareable\Model\Property;

/**
 * Client library for TransUnion - ShareAble for Rentals API.
 */
class Client implements ClientInterface
{

    protected ?string $authToken = null;


    public function __construct(
        protected LoggerInterface $logger,
        protected HttpRequestFactory $requestFactory,
        protected HttpClient $httpClient,
        protected ModelFactory $modelFactory,
        protected string $baseUrl,
        protected string $partnerId,
        protected string $clientId,
        protected string $apiKey
    ) {
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


    public function getLandlord(int $id): Landlord
    {
        $response = $this->request('GET', "Landlords/$id");

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
     * Private functions
     */


    /**
     * @param string[] $data
     */
    protected function encodeJson(array $data): string
    {
        $encoded = json_encode($data);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw new ClientException('Unable to encode JSON: ' . json_last_error_msg());
        }

        return $encoded;
    }


    protected function decodeJson(string $data): mixed
    {
        $decoded = json_decode($data, true);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            throw new ClientException("Unable to decode JSON '$data': " . json_last_error_msg());
        }

        return $decoded;
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
}
