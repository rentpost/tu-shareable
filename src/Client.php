<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactory;
use Psr\Log\LoggerInterface;
use Rentpost\TUShareable\Model\Bundle;

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
            $list[] = new Bundle($row['bundleId'], $row['name']);
        }

        return $list;
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
        $data = json_encode(['clientId' => $this->clientId, 'apiKey' => $this->apiKey]);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->request('POST', 'Tokens', $data, $headers, false);

        $responseData = $this->decodeJson($response);
        $this->authToken = $responseData['token'];
    }


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
