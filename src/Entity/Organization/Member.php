<?php

namespace App\Entity\Organization;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organization__members")
 * @ORM\HasLifecycleCallbacks
 */
class Member implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Organization", inversedBy="members")
     */
    protected $organization;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    protected $user;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $joinedAt;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->joinedAt = new \DateTime();
    }

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
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

    public function jsonSerialize()
    {
        return [
            'organization' => $this->organization,
            'user' => $this->user,
            'joined_at' => $this->joinedAt->format('c')
        ];
    }
}