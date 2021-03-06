<?php

namespace App\Manager\Project;

use App\Entity\Project\Project;
use App\Entity\User\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Utils\Slugger;
use App\Manager\Organization\OrganizationManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Organization\Organization;

class ProjectManager
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var MemberManager */
    protected $memberManager;
    /** @var OrganizationManager */
    protected $organizationManager;
    /** @var Slugger */
    protected $slugger;

    public function __construct(EntityManagerInterface $em, MemberManager $memberManager, OrganizationManager $organizationManager, Slugger $slugger)
    {
        $this->em = $em;
        $this->memberManager = $memberManager;
        $this->organizationManager = $organizationManager;
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

    public function getOrganizationProjects(Organization $organization): array
    {
        return $this->em->getRepository(Project::class)->findByOrganization($organization);
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
            throw new BadRequestHttpException('project.name_already_taken');
        }
        if (!empty($data['organization_slug'])) {
            if (($organization = $this->organizationManager->get($data['organization_slug'])) === null) {
                throw new NotFoundHttpException('organization.not_found');
            }
            $project->setOrganization($organization);
        } else {
            $project->setUser($user);
        }

        $this->em->persist($project);
        $this->em->flush();

        $project->addMember($this->memberManager->create($project, $user));

        return $project;
    }

    public function update(Project $project, array $data)
    {
        $slug = $this->slugger->slugify($data['name']);
        if ($slug !== $project->getSlug() && $this->get($slug) !== null) {
            throw new BadRequestHttpException('project.name_already_taken');
        }
        $project
            ->setName($data['name'])
            ->setSlug($slug)
            ->setWebsiteUrl($data['website_url'])
            ->setShortDescription($data['short_description'])
            ->setDescription($data['description'])
        ;
        if (!empty($data['organization_slug'])) {
            if (($organization = $this->organizationManager->get($data['organization_slug'])) === null) {
                throw new NotFoundHttpException('organization.not_found');
            }
            $project->setOrganization($organization);
        } else {
            $project->setOrganization(null);
        }

        $this->em->flush();
    }
}