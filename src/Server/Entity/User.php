<?php

namespace App\Server\Entity;

use App\Server\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, inversedBy="users")
     */
    private $user_group;

    public function __construct()
    {
        $this->user_group = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getUserGroup(): Collection
    {
        return $this->user_group;
    }

    public function addUserGroup(Group $userGroup): self
    {
        if (!$this->user_group->contains($userGroup)) {
            $this->user_group[] = $userGroup;
        }

        return $this;
    }

    public function removeUserGroup(Group $userGroup): self
    {
        $this->user_group->removeElement($userGroup);

        return $this;
    }
}
