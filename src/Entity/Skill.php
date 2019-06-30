<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="skills")
 */
class Skill
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $type;

    const TYPE_GRAPHICS = 'graphics';
    const TYPE_MANAGEMENT = 'management';
    const TYPE_PROGRAMMING = 'programming';

    const TYPES = [
        self::TYPE_GRAPHICS,
        self::TYPE_MANAGEMENT,
        self::TYPE_PROGRAMMING
    ];

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

    public function setType(string $type): self
    {
        if (!in_array($type, self::TYPES)) {
            throw new \LogicException('invalid_type');
        }
        $this->type = $type;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }
}