<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Skill as BaseSkill;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__job_offers")
 * @ORM\HasLifecycleCallbacks
 */
class JobOffer implements \JsonSerializable
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
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="project.job_offers.empty_title")
     * @Assert\Length(
     *   min = 5,
     *   max = 80,
     *   minMessage = "project.job_offers.title_too_short",
     *   maxMessage = "project.job_offers.title_too_long"
     * )
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $slug;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="project.job_offers.empty_content")
     */
    protected $content;
    /**
     * @ORM\OneToMany(targetEntity="Skill", mappedBy="jobOffer", cascade={"persist", "remove"}, fetch="EAGER")
     */
    protected $skills;
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

    public function __construct()
    {
        $this->skills = new ArrayCollection();
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

    public function addSkill(Skill $skill): self
    {
        $this->skills->add($skill);

        return $this;
    }

    public function hasSkill(Skill $skill): bool
    {
        return $this->skills->contains($skill);
    }

    public function removeSkill(Skill $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    public function findSkill(BaseSkill $skill): ?Skill
    {
        foreach ($this->skills as $jobOfferSkill) {
            if ($jobOfferSkill->getSkill() === $skill) {
                return $jobOfferSkill;
            }
        }
        return null;
    }

    public function getSkills(): Collection
    {
        return $this->skills;
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
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'project' => $this->project,
            'skills' => $this->skills->toArray(),
            'created_at' => $this->createdAt->format('c'),
            'updated_at' => $this->updatedAt->format('c')
        ];
    }
}