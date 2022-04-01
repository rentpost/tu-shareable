<?php

declare(strict_types = 1);

namespace Rentpost\TUShareable;

/**
 * Functions for encoding and decoding JSON with error checking.
 */
trait JsonHelper
{

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
}
