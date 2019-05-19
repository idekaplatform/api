<?php

namespace App\Entity\Organization;

use App\Entity\SocialNetwork as SocialNetworkBase;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organization__social_networks")
 */
class SocialNetwork extends SocialNetworkBase
{
    /**
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="socialNetworks")
     */
    protected $organization;

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }
}