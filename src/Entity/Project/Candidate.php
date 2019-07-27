<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__candidates")
 * @ORM\HasLifecycleCallbacks
 */
class Candidate implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="JobOffer")
     */
    protected $jobOffer;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     */
    protected $user;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $message;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
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

    public function setJobOffer(JobOffer $jobOffer): self
    {
        $this->jobOffer = $jobOffer;

        return $this;
    }

    public function getJobOffer(): JobOffer
    {
        return $this->jobOffer;
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

    public function setMessage(string $message = null): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
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

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'job_offer' => $this->jobOffer,
            'user' => $this->user,
            'message' => $this->message,
            'created_at' => $this->createdAt->format('c')
        ];
    }
}