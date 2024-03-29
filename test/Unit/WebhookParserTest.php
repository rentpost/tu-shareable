<?php

declare(strict_types = 1);

namespace test\Rentpost\TUShareable\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Rentpost\TUShareable\Model\WebhookAuthenticationStatus;
use Rentpost\TUShareable\Model\WebhookReportDelivery;
use Rentpost\TUShareable\WebhookParser;

class WebhookParserTest extends TestCase
{

    public function testParseReportDelivery()
    {
        $data = json_encode([
            'ScreeningRequestRenterId' => 12345,
            'ReportsDeliveryStatus' => 'Success',
        ]);

        $parser = new WebhookParser;
        $request = $this->createMockRequest($data);
        $result = $parser->parseReportDelivery($request);

        $this->assertInstanceOf(WebhookReportDelivery::class, $result);
        $this->assertSame(12345, $result->getScreeningRequestRenterId());
        $this->assertSame('Success', $result->getReportsDeliveryStatus());
    }


    public function testParseAuthenticationStatus()
    {
        $data = json_encode([
            'ScreeningRequestRenterId' => 12345,
            'ManualAuthenticationStatus' => 'Passed',
        ]);

        $parser = new WebhookParser;
        $request = $this->createMockRequest($data);
        $result = $parser->parseAuthenticationStatus($request);

        $this->assertInstanceOf(WebhookAuthenticationStatus::class, $result);
        $this->assertSame(12345, $result->getScreeningRequestRenterId());
        $this->assertSame('Passed', $result->getManualAuthenticationStatus());
    }


    protected function createMockRequest(string $data): RequestInterface
    {
        $stream = $this->createStub(StreamInterface::class);

        $stream->method('getContents')
            ->willReturn($data);

        $request = $this->createStub(RequestInterface::class);

        $request->method('getBody')
            ->willReturn($stream);

        return $request;
    }
}
