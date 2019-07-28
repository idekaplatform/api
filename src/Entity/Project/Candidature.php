<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__candidatures")
 * @ORM\HasLifecycleCallbacks
 */
class Candidature implements \JsonSerializable
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
     * @ORM\Column(type="string", length=20)
     */
    protected $status;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $responder;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $message;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $respondedAt;

    const STATUS_PENDING = 'pending';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->status = self::STATUS_PENDING;
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

    public function setStatus(string $status): self
    {
        if (!in_array($status, [ self::STATUS_PENDING, self::STATUS_CANCELLED, self::STATUS_ACCEPTED, self::STATUS_DECLINED ])) {
            throw new \LogicException('invalid_status');
        }
        $this->status = $status;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setResponder(User $responder = null): self
    {
        $this->responder = $responder;

        return $this;
    }

    public function getResponder(): ?User
    {
        return $this->responder;
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

    public function setRespondedAt(\DateTime $respondedAt = null): self
    {
        $this->respondedAt = $respondedAt;

        return $this;
    }

    public function getRespondedAt(): ?\DateTime
    {
        return $this->respondedAt;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'job_offer' => $this->jobOffer,
            'user' => $this->user,
            'responder' => $this->responder,
            'status' => $this->status,
            'message' => $this->message,
            'created_at' => $this->createdAt->format('c'),
            'responded_at' => ($this->respondedAt !== null) ? $this->respondedAt->format('c') : null
        ];
    }
}