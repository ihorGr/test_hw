<?php

declare(strict_types=1);

namespace App\Server\Api\Request\Group;

use App\Server\Api\Request\ApiRequestInterface;
use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Api\Request\Group\VariableField\FieldsModel\EditFieldsModel;
use Symfony\Component\Validator\Constraints as Assert;

class EditGroupRequest implements ApiRequestInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $newName;

    private VariableField $filter;

    /**
     * @param string|null $newName
     */
    private function __construct(?array $data, VariableField $filter)
    {
        $this->newName = $data['name'] ?? null;
        $this->filter = $filter;
    }

    /**
     * @return string
     */
    public function getNewName(): string
    {
        return $this->newName;
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
        $data = $requestData['data'] ?? null;
        $filterData = $requestData['filter'] ?? [];

        $filter = new VariableField($filterData);

        if (!in_array($filter->getField(), EditFieldsModel::ALLOWED_FIELDS)) {
            throw new \RuntimeException('Unknown filter field');
        }

        return new self($data, $filter);
    }
}