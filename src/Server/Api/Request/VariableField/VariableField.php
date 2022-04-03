<?php

declare(strict_types=1);

namespace App\Server\Api\Request\VariableField;

use Symfony\Component\Validator\Constraints as Assert;

class VariableField
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $field;

    /**
     * @Assert\NotBlank
     */
    private $value;

    /**
     * @param string $field
     * @param mixed $value
     */
    public function __construct(array $variableData)
    {
        if ([] === $variableData) {
            throw new \RuntimeException('Error! Request part is missing.');
        }

        $this->field = key($variableData) ?? null;
        $this->value = $this->setValue($variableData);
    }

    private function setValue($variableData)
    {
        if (is_array($variableData[$this->field])) {
            return new self($variableData[$this->field]);
        }

        return $variableData[$this->field] ?? null;
    }

    /**
     * @return null|string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return null|mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}