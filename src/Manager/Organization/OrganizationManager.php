<?php

namespace App\Manager\Organization;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Organization\Member;
use App\Entity\Organization\Organization;
use App\Entity\User\User;

class OrganizationManager
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
}