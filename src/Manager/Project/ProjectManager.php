<?php

namespace App\Manager\Project;

use App\Entity\Project\Project;
use App\Entity\User\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Utils\Slugger;

class ProjectManager
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var Slugger */
    protected $slugger;

    public function __construct(EntityManagerInterface $em, Slugger $slugger)
    {
        $this->em = $em;
        $this->slugger = $slugger;
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

    public function create(array $data, User $user): Project
    {
        $project = (new Project())
            ->setName($data['name'])
            ->setSlug($this->slugger->slugify($data['name']))
            ->setWebsiteUrl($data['website_url'])
            ->setShortDescription($data['short_description'])
            ->setDescription($data['description'])
        ;
        if ($this->get($project->getSlug()) !== null) {
            throw new BadRequestHttpException('projects.name_already_taken');
        }
        if (!empty($data['organization_id'])) {
            if (($organization = $this->organizationManager->get($data['organization_id'])) === null) {
                throw new NotFoundHttpException('organizations.not_found');
            }
            $project->setOrganization($organization);
        } else {
            $project->setUser($user);
        }

        $this->em->persist($project);
        $this->em->flush();

        return $project;
    }
}