<?php

declare(strict_types=1);

namespace App\Server\Writer\Group;

use App\Server\Api\Request\Group\AddGroupRequest;
use App\Server\Api\Request\Group\DeleteGroupRequest;
use App\Server\Api\Request\Group\EditGroupRequest;

interface GroupWriterInterface
{
    public function insert(AddGroupRequest $addGroupRequest): void;

    public function update(EditGroupRequest $editGroupRequest): void;

    public function delete(DeleteGroupRequest $deleteGroupRequest): void;
}