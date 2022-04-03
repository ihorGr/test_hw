<?php

declare(strict_types=1);

namespace App\Server\Api\Request\User\VariableField\FieldsModel;

class DeleteUserGroupFieldsModel
{
    public const ALLOWED_USER_FIELDS = ['id', 'email'];

    public const ALLOWED_GROUP_FIELDS = ['id' , 'name'];
}