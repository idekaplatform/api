<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Project\Project;
use App\Entity\User\User;
use App\Entity\Project\Member;

class MemberManager
{
    /** @var EntityManagerInterface */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(Project $project, User $user): Member
    {
        $member =
            (new Member())
            ->setProject($project)
            ->setUser($user)
        ;
        $this->em->persist($member);
        $this->em->flush();
        return $member;
    }
}
