<?php

declare(strict_types=1);

namespace App\Server\Writer\Group;

use App\Serializer\SerializerInterface;
use App\Server\Api\Request\Group\AddGroupRequest;
use App\Server\Api\Request\Group\DeleteGroupRequest;
use App\Server\Api\Request\Group\EditGroupRequest;
use App\Server\Entity\Group;
use App\Server\Provider\Group\GroupProviderInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;

class GroupWriter implements GroupWriterInterface
{
    /** @var ManagerRegistry */
    protected $doctrine;

    /** @var LoggerInterface */
    protected $logger;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var ObjectManager */
    protected $groupManager;

    /** @var GroupProviderInterface */
    protected $groupProvider;

    public function __construct(ManagerRegistry $doctrine, LoggerInterface $logger, SerializerInterface $serializer, GroupProviderInterface $groupProvider)
    {
        $this->doctrine = $doctrine;
        $this->logger   = $logger;
        $this->serializer = $serializer;
        $this->groupManager  = $this->doctrine->getManagerForClass(Group::class);
        $this->groupProvider  = $groupProvider;
    }

    public function insert(AddGroupRequest $addGroupRequest): void
    {
        $newGroup = new Group();

        $newGroup
            ->setName($addGroupRequest->getName());

        $this->groupManager->persist($newGroup);
        try {
            $this->groupManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \RuntimeException('Group with this name is already exist');
        }

    }

    /**
     * @param EditGroupRequest $editGroupRequest
     * @return void
     */
    public function update(EditGroupRequest $editGroupRequest): void
    {
        $group = $this->groupProvider->getGroupByFilter($editGroupRequest->getFilter());

        $group->setName($editGroupRequest->getNewName());

        $this->groupManager->flush();
    }

    /**
     * @param DeleteGroupRequest $deleteGroupRequest
     * @return void
     */
    public function delete(DeleteGroupRequest $deleteGroupRequest): void
    {
        $group = $this->groupProvider->getGroupByFilter($deleteGroupRequest->getFilter());

        $this->groupManager->remove($group);
        $this->groupManager->flush();
    }
}