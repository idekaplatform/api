<?php

namespace App\Manager\Project;

use App\Entity\Project\Project;

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
}