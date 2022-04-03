<?php

declare(strict_types=1);

namespace App\Server\Writer\User;

use App\Server\Api\Request\User\AddUserRequest;
use App\Server\Api\Request\User\DeleteUserRequest;
use App\Server\Api\Request\User\EditUserRequest;

interface UserWriterInterface
{
    public function insert(AddUserRequest $addUserRequest): void;

    public function update(EditUserRequest $editUserRequest): void;

    public function delete(DeleteUserRequest $deleteUserRequest): void;
}