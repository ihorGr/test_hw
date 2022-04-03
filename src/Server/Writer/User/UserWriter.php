<?php

declare(strict_types=1);

namespace App\Server\Writer\User;

use App\Server\Entity\User;
use App\Serializer\SerializerInterface;
use App\Server\Api\Request\User\AddUserGroupRequest;
use App\Server\Api\Request\User\AddUserRequest;
use App\Server\Api\Request\User\DeleteUserGroupRequest;
use App\Server\Api\Request\User\DeleteUserRequest;
use App\Server\Api\Request\User\EditUserRequest;
use App\Server\Provider\Group\GroupProviderInterface;
use App\Server\Provider\User\UserProviderInterface;
use App\Server\Writer\FieldFactory\FieldFactoryInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class UserWriter implements UserWriterInterface
{
    /** @var ManagerRegistry */
    protected $doctrine;

    /** @var LoggerInterface */
    protected $logger;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var ObjectManager */
    protected $userManager;

    /** @var GroupProviderInterface */
    protected $groupProvider;

    /** @var UserProviderInterface */
    protected $userProvider;

    /** @var FieldFactoryInterface */
    protected $fieldFactory;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, SerializerInterface $serializer, GroupProviderInterface $groupProvider, UserProviderInterface $userProvider, FieldFactoryInterface $fieldFactory)
    {
        $this->doctrine = $doctrine;
        $this->logger   = $logger;
        $this->serializer = $serializer;
        $this->userManager  = $this->doctrine->getManagerForClass(User::class);
        $this->groupProvider  = $groupProvider;
        $this->userProvider  = $userProvider;
        $this->fieldFactory  = $fieldFactory;
    }

    public function insert(AddUserRequest $addUserRequest): void
    {
        $group =$this->groupProvider->getGroupByFilter($addUserRequest->getGroup());

        $newUser = new User();

        $newUser
            ->setName($addUserRequest->getName())
            ->setEmail($addUserRequest->getEmail())
            ->addUserGroup($group);

        $this->userManager->persist($newUser);

        try {
            $this->userManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \RuntimeException(sprintf('User with this email: %s is already exist', $addUserRequest->getEmail()));
        }

    }

    public function update(EditUserRequest $editUserRequest): void
    {
        $user = $this->userProvider->getUserByFilter($editUserRequest->getFilter());

        $this->fieldFactory->fieldSetter($editUserRequest->getEditedData(), $user);

        try {
            $this->userManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \RuntimeException(sprintf('User with this email: %s is already exist', $editUserRequest->getEditedData()->getValue()));
        }

    }

    public function delete(DeleteUserRequest $deleteUserRequest): void
    {
        $user = $this->userProvider->getUserByFilter($deleteUserRequest->getFilter());

        $this->userManager->remove($user);
        $this->userManager->flush();
    }

    public function addUserGroup(AddUserGroupRequest $addUserGroupRequest): void
    {
        $user = $this->userProvider->getUserByFilter($addUserGroupRequest->getUser());

        $group =$this->groupProvider->getGroupByFilter($addUserGroupRequest->getGroup());

        $user->addUserGroup($group);

        $this->userManager->flush();
    }

    public function deleteUserGroup(DeleteUserGroupRequest $deleteUserGroupRequest): void
    {
        $user = $this->userProvider->getUserByFilter($deleteUserGroupRequest->getUser());

        $group =$this->groupProvider->getGroupByFilter($deleteUserGroupRequest->getGroup());

        $user->removeUserGroup($group);

        $this->userManager->flush();
    }

}