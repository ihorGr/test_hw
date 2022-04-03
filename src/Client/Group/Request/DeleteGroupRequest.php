<?php

declare(strict_types=1);

namespace App\Client\Group\Request;

class DeleteGroupRequest
{
    /** @var string */
    private $field;

    /** @var int|string */
    private $value;

    /**
     * @param string $field
     * @param int|string $value
     */
    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return int|string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            'field' => $this->field,
            'value' => $this->value,
        ];
    }

    public function getRequestData(array $apiKey)
    {
        return [
            'api_key' => $apiKey[0] ?? null,
            'filter' => [
                $this->field => $this->value
            ]
        ];
    }
}