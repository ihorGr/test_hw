<?php

declare(strict_types=1);

namespace App\Server\Api\Request\User;

use App\Server\Api\Request\ApiRequestInterface;
use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Api\Request\User\VariableField\FieldsModel\DeleteFieldsModel;

class DeleteUserRequest implements ApiRequestInterface
{
    private VariableField $filter;

    /**
     * @param string|null $newName
     */
    private function __construct(VariableField $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return VariableField
     */
    public function getFilter(): VariableField
    {
        return $this->filter;
    }

    public static function fromRequestData(array $requestData): self
    {
        $filter = $requestData['filter'] ?? null;

        $filter = new VariableField($filter);

        if (!in_array($filter->getField(), DeleteFieldsModel::ALLOWED_FIELDS)) {
            throw new \RuntimeException('Unknown filter field');
        }

        return new self($filter);
    }
}