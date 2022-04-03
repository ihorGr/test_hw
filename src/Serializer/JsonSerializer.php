<?php

declare(strict_types = 1);

namespace App\Serializer;

use App\Serializer\Exception\SerializerException;

class JsonSerializer implements SerializerInterface
{
    /**
     * {@inheritDoc}
     */
    public function toArray(string $str): array
    {
        $result = json_decode($str, true);
        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw SerializerException::fromDecodeError(json_last_error_msg());
        }

        if (!\is_array($result)) {
            $invalidType = gettype($result);

            throw SerializerException::fromDecodeError(
                sprintf('JsonSerializer: decode result is not array, but %s', $invalidType)
            );
        }

        return $result;
    }

    public function toString(array $arr) : string
    {
        $result = json_encode($arr);

        if (\JSON_ERROR_INF_OR_NAN === json_last_error()) {
            $result = json_encode(unserialize(str_replace(array('NAN;','INF;'),'0;',serialize($arr))));
        }

        return $result;
    }
}
