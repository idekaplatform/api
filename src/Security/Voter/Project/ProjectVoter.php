<?php

namespace App\Security\Voter\Project;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use App\Entity\Project\Project;
use App\Entity\User\User;

class ProjectVoter extends Voter
{
    const UPDATE = 'project_update';
    const DELETE = 'project_delete';

    public function supports($attribute, $subject)
    {
        return $subject instanceof Project && in_array($attribute, [ self::UPDATE, self::DELETE ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::UPDATE:
                return $this->canUpdateProject($subject, $user);
            case self::DELETE:
                return $this->canDeleteProject($subject, $user);
        }
        throw new \LogicException('This code should not be reached');
    }

    protected function canUpdateProject(Project $project, User $user): bool
    {
        return $project->isTeamMember($user);
    }

    protected function canDeleteProject(Project $project, User $user): bool
    {
        return $project->isTeamMember($user);
    }
}