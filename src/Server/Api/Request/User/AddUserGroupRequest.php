<?php

declare(strict_types=1);

namespace App\Server\Api\Request\User;

use \App\Server\Api\Request\ApiRequestInterface;
use App\Server\Api\Request\User\VariableField\FieldsModel\AddUserGroupFieldsModel;
use App\Server\Api\Request\VariableField\VariableField;

class AddUserGroupRequest implements ApiRequestInterface
{
    private VariableField $user;

    private VariableField $group;

    /**
     * @param VariableField $user
     * @param VariableField $group
     */
    private function __construct(VariableField $user,  VariableField $group)
    {
        $this->user = $user;
        $this->group = $group;
    }

    /**
     * @return VariableField
     */
    public function getUser(): VariableField
    {
        return $this->user;
    }

    /**
     * @return VariableField
     */
    public function getGroup(): VariableField
    {
        return $this->group;
    }

    public static function fromRequestData(array $requestData): self
    {
        $data = $requestData['data'] ?? null;

        $user = new VariableField($data['user'] ?? []);

        if (!in_array($user->getField(), AddUserGroupFieldsModel::ALLOWED_USER_FIELDS)) {
            throw new \RuntimeException('Unknown user field');
        }

        $group = new VariableField($data['group'] ?? []);

        if (!in_array($group->getField(), AddUserGroupFieldsModel::ALLOWED_GROUP_FIELDS)) {
            throw new \RuntimeException('Unknown group field');
        }

        return new static($user, $group);
    }
}