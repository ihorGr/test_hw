<?php

declare(strict_types=1);

namespace App\Client\InternalClient\Exception;

class InternalApiException extends \RuntimeException
{
    public static function fromPreviousException(\Throwable $exception): self
    {
        //TODO: implement
    }
}
