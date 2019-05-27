<?php

namespace App\Manager\Organization;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Organization\Member;
use App\Entity\Organization\Organization;
use App\Entity\User\User;
use App\Utils\Slugger;

class OrganizationManager
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

    public function get(string $slug): ?Organization
    {
        return $this->em->getRepository(Organization::class)->findOneBySlug($slug);
    }

    public function getMemberOrganizations(User $user)
    {
        return array_map(function(Member $member) {
            return $member->getOrganization();
        }, $this->em->getRepository(Member::class)->findByUser($user));
    }

    public function create(array $data, User $user): Organization
    {
        $organization = (new Organization())
            ->setName($data['name'])
            ->setSlug($this->slugger->slugify($data['name']))
            ->setShortDescription($data['short_description'])
            ->setDescription($data['description'])
            ->setWebsiteUrl($data['website_url'])
        ;
        $organization->addMember((new Member())
            ->setUser($user)
            ->setOrganization($organization))
        ;
        $this->em->persist($organization);
        $this->em->flush();

        return $organization;
    }
}