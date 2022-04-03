<?php

namespace App\Client\Request;

interface ClientApiRequestInterface
{
    public function getRequestData(array $apiKey);
}