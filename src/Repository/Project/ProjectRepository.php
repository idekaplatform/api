<?php

namespace App\Repository\Project;

use Doctrine\ORM\EntityRepository;
use App\Entity\User\User;

class ProjectRepository extends EntityRepository
{
    public function getUserProjects(User $user): array
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p')
            ->join('p.organization', 'o')
            ->join('o.members', 'm')
            ->where('m.user = :member_user')
            ->orWhere('p.user = :project_user')
            ->setParameters([
                'member_user' => $user,
                'project_user' => $user
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}