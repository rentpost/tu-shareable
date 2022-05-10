<?php

require(__DIR__ . '/../vendor/autoload.php');

function getShareableClient()
{
    $logger = new Monolog\Logger('TUShareable');
    $logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/log.txt'));

    $requestFactory = new GuzzleHttp\Psr7\HttpFactory;
    $httpClient = new GuzzleHttp\Client;

    $config = parse_ini_file(__DIR__ . '/config');

    return new Rentpost\TUShareable\Client(
        $logger,
        $requestFactory,
        $httpClient,
        $config['url'],
        $config['clientId'],
        $config['apiKey']
    );
}
