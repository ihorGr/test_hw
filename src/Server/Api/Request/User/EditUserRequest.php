<?php

declare(strict_types=1);

namespace App\Server\Api\Request\User;

use App\Server\Api\Request\ApiRequestInterface;
use App\Server\Api\Request\User\VariableField\FieldsModel\EditFieldsModel;
use App\Server\Api\Request\VariableField\VariableField;

class EditUserRequest implements ApiRequestInterface
{
    private VariableField $editedData;

    private VariableField $filter;

    /**
     * @param string|null $newName
     */
    private function __construct(VariableField $editedData, VariableField $filter)
    {
        $this->editedData = $editedData;
        $this->filter = $filter;
    }

    /**
     * @return VariableField
     */
    public function getEditedData(): VariableField
    {
        return $this->editedData;
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
        $data = $requestData['data'] ?? [];
        $filterData = $requestData['filter'] ?? [];

        $data = new VariableField($data);

        if (!in_array($data->getField(), EditFieldsModel::ALLOWED_DATA_FIELDS)) {
            throw new \RuntimeException('Unknown data field');
        }

        $filter = new VariableField($filterData);

        if (!in_array($filter->getField(), EditFieldsModel::ALLOWED_FILTER_FIELDS)) {
            throw new \RuntimeException('Unknown filter field');
        }

        return new self($data, $filter);
    }

}