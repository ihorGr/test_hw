<?php

declare(strict_types=1);

namespace App\Server\Provider\Group;

use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Entity\Group;
use App\Server\Repository\GroupRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class GroupProvider implements GroupProviderInterface
{
    /** @var ManagerRegistry */
    protected $doctrine;

    public function __construct(Connection $connection, ManagerRegistry $doctrine)
    {
        $this->connection = $connection;
        $this->doctrine = $doctrine;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'g.id',
                'g.name'
            )
            ->from('`group`', 'g')
            ->orderBy('g.id', 'DESC' )
            ->execute();

        return $stmt->fetchAllAssociative();
    }


    public function findOneByFilter(VariableField $filter): ?Group
    {
        return $this->getGroupRepository()->findOneBy(
            [$filter->getField() => $filter->getValue()]
        );
    }

    public function getGroupByFilter(VariableField $filter): Group
    {
        $group = $this->findOneByFilter($filter);

        if (null === $group) {
            throw new EntityNotFoundException(sprintf('Cant find group by %s:%s', $filter->getField(), $filter->getValue()));
        }

        return $group;
    }

    protected function getGroupRepository(): GroupRepository
    {
        return $this->doctrine->getRepository(Group::class);
    }

}