<?php

namespace App\Entity\Project;

use App\Entity\Skill as BaseSkill;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__skills")
 */
class Skill implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="JobOffer", inversedBy="skills")
     */
    protected $jobOffer;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Skill")
     */
    protected $skill;
    /**
     * @ORM\Column(type="integer")
     */
    protected $level;

    public function setJobOffer(JobOffer $jobOffer): self
    {
        $this->jobOffer = $jobOffer;

        return $this;
    }

    public function getJobOffer(): JobOffer
    {
        return $this->jobOffer;
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

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function jsonSerialize()
    {
        return [
            'skill' => $this->skill,
            'level' => $this->level,
        ];
    }
}