<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\PublishableInterface;
use App\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__news")
 * @ORM\HasLifecycleCallbacks
 */
class News implements \JsonSerializable, PublishableInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     */
    protected $project;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    protected $author;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;
    /**
     * @ORM\Column(type="text")
     */
    protected $content;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isPublished = false;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $publishedAt;
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

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
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

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
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

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function publish(): PublishableInterface
    {
        $this->isPublished = true;
        $this->publishedAt = new \DateTime();

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
        return $this->updatedAt;
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
            'project' => $this->project,
            'author' => $this->author,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_published' => $this->isPublished,
            'published_at' => $this->publishedAt,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}