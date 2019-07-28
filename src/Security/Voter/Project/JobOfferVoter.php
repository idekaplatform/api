<?php

namespace App\Security\Voter\Project;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use App\Entity\Project\Project;
use App\Entity\User\User;
use App\Entity\Project\JobOffer;

class JobOfferVoter extends Voter
{
    const CREATE = 'job_offer_create';
    const ADD_SKILL = 'job_offer_add_skill';
    const REMOVE_SKILL = 'job_offer_remove_skill';
    const UPDATE_SKILL = 'job_offer_update_skill';

    public function supports($attribute, $subject)
    {
        if ($subject instanceof Project && in_array($attribute, [ self::CREATE ])) {
            return true;
        }
        if ($subject instanceof JobOffer && in_array($attribute, [ self::ADD_SKILL, self::REMOVE_SKILL, self::UPDATE_SKILL ])) {
            return true;
        }
        return false;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreateJobOffer($subject, $user);
            case self::ADD_SKILL:
                return $this->canAddSkill($subject, $user);
            case self::REMOVE_SKILL:
                return $this->canRemoveSkill($subject, $user);
            case self::UPDATE_SKILL:
                return $this->canUpdateSkill($subject, $user);
        }
        throw new \LogicException('This code should not be reached');
    }

    protected function canCreateJobOffer(Project $project, User $user): bool
    {
        return $project->isTeamMember($user);
    }

    protected function canAddSkill(JobOffer $jobOffer, User $user): bool
    {
        return $jobOffer->getProject()->isTeamMember($user);
    }

    protected function canRemoveSkill(JobOffer $jobOffer, User $user): bool
    {
        return $jobOffer->getProject()->isTeamMember($user);
    }

    protected function canUpdateSkill(JobOffer $jobOffer, User $user): bool
    {
        return $jobOffer->getProject()->isTeamMember($user);
    }
}