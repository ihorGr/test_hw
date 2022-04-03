<?php

declare(strict_types=1);

namespace App\Server\Provider\User;

use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Entity\User;

interface UserProviderInterface
{
    public function findAll(): array;

    public function findByGroupId(int $id): array;

    public function findOneByFilter(VariableField $filter): ?User;
}