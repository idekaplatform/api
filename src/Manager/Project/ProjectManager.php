<?php

namespace App\Manager\Project;

use App\Entity\Project\Project;
use App\Entity\User\User;

use Doctrine\ORM\EntityManagerInterface;

class ProjectManager
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAll()
    {
        return $this->em->getRepository(Project::class)->findAll();
    }

    public function get(string $slug)
    {
        return $this->em->getRepository(Project::class)->findOneBySlug($slug);
    }

    public function getUserProjects(User $user): array
    {
        return $this->em->getRepository(Project::class)->getUserProjects($user);
    }
}