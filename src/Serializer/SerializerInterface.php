<?php

declare(strict_types = 1);

namespace App\Serializer;

use App\Serializer\Exception\SerializerException;

interface SerializerInterface
{
    /**
     * @param string $str
     * @return array
     *
     * @throws SerializerException
     */
    public function toArray(string $str): array;

    public function toString(array $arr): string;
}
