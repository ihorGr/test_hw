<?php

declare(strict_types=1);

namespace App\Server\Provider\Group;

use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Entity\Group;

interface GroupProviderInterface
{
    public function findAll(): array;

    public function findOneByFilter(VariableField $filter): ?Group;

    public function getGroupByFilter(VariableField $filter): Group;
}