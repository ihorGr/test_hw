<?php

declare(strict_types=1);

namespace App\Serializer\Exception;

class SerializerException extends \RuntimeException
{
    public const ERROR_DECODE = 'Decode error: %s';

    public static function fromDecodeError(string $errorMessage): self
    {
        return new self(sprintf(self::ERROR_DECODE, $errorMessage));
    }
}
