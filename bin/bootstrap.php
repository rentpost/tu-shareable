<?php

declare(strict_types = 1);

use GuzzleHttp\Psr7\HttpFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Rentpost\TUShareable\Client;

require __DIR__ . '/../vendor/autoload.php';


function getShareableClient(): Client
{
    $logger = new Logger('TUShareable');
    $logger->pushHandler(new StreamHandler('php://stdout', Logger::ERROR));

    $requestFactory = new HttpFactory;
    $httpClient = new GuzzleHttp\Client;

    $config = parse_ini_file(__DIR__ . '/../config');

    return new Client(
        $logger,
        $requestFactory,
        $httpClient,
        $config['url'],
        $config['clientId'],
        $config['apiKeyOne'],
        $config['apiKeyTwo'],
    );
}
