<?php

declare(strict_types=1);

namespace App\Server\Api\Request\User\VariableField\FieldsModel;

class EditFieldsModel
{
    public const ALLOWED_DATA_FIELDS = ['name', 'email'];
    public const ALLOWED_FILTER_FIELDS = ['id', 'email'];

}