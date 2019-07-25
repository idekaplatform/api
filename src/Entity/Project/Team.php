<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__teams")
 * @ORM\HasLifecycleCallbacks
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $name;
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     */
    protected $project;
    /**
     * @ORM\Column(type="array")
     */
    protected $permissions;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function addPermission(string $permission): self
    {
        $this->permissions->add($permission);

        return $this;
    }

    public function removePermission(string $permission): self
    {
        $this->permissions->removeElement($permission);

        return $this;
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions->contains($permission);
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}