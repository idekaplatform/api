<?php

namespace App\Entity\Project;

use App\Entity\SocialNetwork as SocialNetworkBase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__social_networks")
 */
class SocialNetwork extends SocialNetworkBase
{
    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="socialNetworks")
     */
    protected $project;

    public function setProject(Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}