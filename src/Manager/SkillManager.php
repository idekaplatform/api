<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Skill;

class SkillManager
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAll(): array
    {
        return $this->em->getRepository(Skill::class)->findAll();
    }

    public function get(int $id): ?Skill
    {
        return $this->em->getRepository(Skill::class)->find($id);
    }
}