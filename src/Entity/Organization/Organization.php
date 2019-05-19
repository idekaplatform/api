<?php

namespace App\Entity\Organization;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organization__organizations")
 * @ORM\HasLifecycleCallbacks
 */
class Organization implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=150, unique=true)
     */
    protected $slug;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $shortDescription;
    /**
     * @ORM\Column(type="text")
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $websiteUrl;
    /**
     * @ORM\OneToMany(targetEntity="Member", mappedBy="organization", cascade={"persist", "remove"})
     */
    protected $members;
    /**
     * @ORM\OneToMany(targetEntity="SocialNetwork", mappedBy="organization", cascade={"persist", "remove"})
     */
    protected $socialNetworks;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->socialNetworks = new ArrayCollection();
    }

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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setWebsiteUrl(string $websiteUrl = null): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function addMember(Member $member): self
    {
        $this->members->add($member);

        return $this;
    }

    public function removeMember(Member $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addSocialNetwork(SocialNetwork $socialNetwork): self
    {
        $this->socialNetworks->add($socialNetwork);

        return $this;
    }

    public function removeSocialNetwork(SocialNetwork $socialNetwork): self
    {
        $this->socialNetworks->removeElement($socialNetwork);

        return $this;
    }

    public function getSocialNetworks(): Collection
    {
        return $this->socialNetworks;
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

    public function setUpdatedAt(\DateTime $updatedAt): self
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
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->shortDescription,
            'description' => $this->description,
            'members' => $this->members,
            'social_networks' => $this->socialNetworks,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}