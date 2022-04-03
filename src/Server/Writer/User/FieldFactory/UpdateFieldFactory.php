<?php

declare(strict_types=1);

namespace App\Server\Writer\User\FieldFactory;

use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Entity\User;
use App\Server\Writer\FieldFactory\FieldFactoryInterface;

class UpdateFieldFactory implements FieldFactoryInterface
{
    private const NAME = 'name';
    private const EMAIL = 'email';

    public function fieldSetter(VariableField $variableField, User $user): void
    {
        switch ($variableField->getField()) {
            case self::NAME:
                $user->setName($variableField->getValue());
                break;
            case self::EMAIL:
                $user->setEmail($variableField->getValue());
                break;
            default:
                break;
        }

    }
}