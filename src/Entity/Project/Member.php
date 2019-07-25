<?php

namespace App\Entity\Project;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__members")
 * @ORM\HasLifecycleCallbacks
 */
class Member
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="members")
     */
    protected $project;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    protected $user;
    /**
     * @ORM\ManyToMany(targetEntity="Team")
     * @ORM\JoinTable(name="project__team_members")
     */
    protected $teams;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $joinedAt;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->joinedAt = new \DateTime();
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

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->isInTeam($team)) {
            $this->teams->add($team);
        }
        return $this;
    }

    public function isInTeam(Team $team): bool
    {
        return $this->teams->contains($team);
    }

    public function removeTeam(Team $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function setJoinedAt(\DateTime $joinedAt): self
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    public function getJoinedAt(): \DateTime
    {
        return $this->joinedAt;
    }
}