<?php

declare(strict_types=1);

namespace App\Server\Writer\FieldFactory;

use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Entity\User;

interface FieldFactoryInterface
{
    public function fieldSetter(VariableField $variableField, User $user): void;
}