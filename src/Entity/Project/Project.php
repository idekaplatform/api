<?php

namespace App\Entity\Project;

use App\Entity\PublishableInterface;
use App\Entity\SocialNetwork;
use App\Entity\Organization\Organization;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\ProjectRepository")
 * @ORM\Table(name="project__projects")
 * @ORM\HasLifecycleCallbacks
 */
class Project implements \JsonSerializable, PublishableInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    protected $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization\Organization")
     */
    protected $organization;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User\User")
     * @ORM\JoinTable(name="project__members")
     */
    protected $members;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $websiteUrl;
    /**
     * @ORM\OneToMany(targetEntity="SocialNetwork", mappedBy="project", cascade={"persist", "remove"})
     */
    protected $socialNetworks;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isPublished;
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
        $this->socialNetworks = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->isPublished = false;
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

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
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

    public function isTeamMember(User $user): bool
    {
        if ($this->user === $user) {
            return true;
        }
        if ($this->organization === null) {
            return false;
        }
        foreach ($this->organization->getMembers() as $member) {
            if ($member->getUser() === $user) {
                return true;
            }
        }
        return false;
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

    public function getSocialNetworks()
    {
        return $this->socialNetworks;
    }

    public function addMember(User $user): self
    {
        $this->members->add($user);

        return $this;
    }

    public function hasMember(User $user): bool
    {
        return $this->members->contains($user);
    }

    public function removeUser(User $user): self
    {
        $this->members->removeElement($user);

        return $this;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function publish(): PublishableInterface
    {
        $this->isPublished = true;

        return $this;
    }

    public function unpublish(): PublishableInterface
    {
        $this->isPublished = false;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
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
            'user' => $this->user,
            'organization' => $this->organization,
            'website_url' => $this->websiteUrl,
            'social_networks' => $this->socialNetworks->toArray(),
            'is_published' => $this->isPublished,
            'created_at' => $this->createdAt->format('c'),
            'updated_at' => $this->updatedAt->format('c')
        ];
    }
}