<?php

declare(strict_types=1);

namespace App\Server\Provider\User;

use App\Server\Api\Request\VariableField\VariableField;
use App\Server\Entity\User;
use App\Server\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class UserProvider implements UserProviderInterface
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
                'u.id',
                'u.name',
                'u.email',
                'ug.group_id',
                'g.name as group_name'
            )
            ->from('user', 'u')
            ->leftJoin('u', 'user_group', 'ug', 'u.id = ug.user_id')
            ->leftJoin('ug', '`group`', 'g', 'ug.group_id = g.id')
            ->orderBy('u.name', 'ASC' )
            ->execute();

        return $stmt->fetchAllAssociative();
    }

    public function findByGroupId(int $groupId): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'u.id',
                'u.name',
                'u.email',
                'ug.group_id',
                'g.name as group_name'
            )
            ->from('user', 'u')
            ->innerJoin('u', 'user_group', 'ug', 'u.id = ug.user_id')
            ->innerJoin('ug', '`group`', 'g', 'ug.group_id = g.id')
            ->where('ug.group_id = :group_id')
            ->setParameter(':group_id', $groupId)
            ->execute();

        return $stmt->fetchAllAssociative();
    }

    public function findOneByFilter(VariableField $filter): ?User
    {
        return $this->getUserRepository()->findOneBy(
            [$filter->getField() => $filter->getValue()]
        );
    }

    public function getUserByFilter(VariableField $filter): User
    {
        $user = $this->findOneByFilter($filter);

        if (null === $user) {
            throw new EntityNotFoundException(sprintf('Cant find user by %s:%s', $filter->getField(), $filter->getValue()) );
        }

        return $user;
    }

    protected function getUserRepository(): UserRepository
    {
        return $this->doctrine->getRepository(User::class);
    }

}