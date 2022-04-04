<?php

namespace ZohoApiClient;

class ZohoApiClientException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct(
            sprintf("ZohoApiClient: %s", $message)
        );
    }
}