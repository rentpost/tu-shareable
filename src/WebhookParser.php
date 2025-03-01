<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

use Psr\Http\Message\RequestInterface;
use Rentpost\TUShareable\Model\WebhookAuthenticationStatus;
use Rentpost\TUShareable\Model\WebhookReportDelivery;

/**
 * Class to parse webhooks sent by ShareAble.
 */
class WebhookParser
{

    use JsonHelper;


    public function parseReportDelivery(RequestInterface $request): WebhookReportDelivery
    {
        $body = $request->getBody()->getContents();

        $data = $this->decodeJson($body);

        return new WebhookReportDelivery(
            $data['ScreeningRequestRenterId'],
            $data['ReportsDeliveryStatus'],
        );
    }


    public function parseAuthenticationStatus(RequestInterface $request): WebhookAuthenticationStatus
    {
        $body = $request->getBody()->getContents();

        $data = $this->decodeJson($body);

        return new WebhookAuthenticationStatus(
            $data['ScreeningRequestRenterId'],
            $data['ManualAuthenticationStatus'],
        );
    }
}
