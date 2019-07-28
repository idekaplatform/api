<?php

namespace App\Entity\User;

use App\Entity\Skill as BaseSkill;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user__skills")
 * @ORM\HasLifecycleCallbacks
 */
class Skill implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Skill")
     */
    protected $skill;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;
    /**
     * @ORM\Column(type="integer")
     */
    protected $selfEvaluation;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function setSkill(BaseSkill $skill): self
    {
        $this->skill = $skill;
        
        return $this;
    }

    public function getSkill(): BaseSkill
    {
        return $this->skill;
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

    public function setSelfEvaluation(int $selfEvaluation): self
    {
        $this->selfEvaluation = $selfEvaluation;

        return $this;
    }

    public function getSelfEvaluation(): int
    {
        return $this->selfEvaluation;
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

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function jsonSerialize()
    {
        return [
            'skill' => $this->skill,
            'self_evaluation' => $this->selfEvaluation,
            'created_at' => $this->createdAt->format('c'),
            'updated_at' => $this->updatedAt->format('c')
        ];
    }
}