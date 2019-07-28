<?php

namespace App\Security\Voter\Project;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\Project\Candidature;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\User\User;

class CandidatureVoter extends Voter
{
    const CANCEL = 'project_candidature_cancel';
    const RESPOND = 'project_candidature_respond';

    public function supports($attribute, $subject)
    {
        return $subject instanceof Candidature && in_array($attribute, [ self::CANCEL, self::RESPOND ]);
    }

    public function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CANCEL:
                return $this->canCancelCandidature($subject, $user);
            case self::RESPOND:
                return $this->canRespondToCandidature($subject, $user);
        }
        throw new \LogicException('This code should not be reached');
    }

    public function canCancelCandidature(Candidature $candidature, User $user)
    {
        return $candidature->getStatus() === Candidature::STATUS_PENDING && $candidature->getUser() === $user;
    }

    public function canRespondToCandidature(Candidature $candidature, User $user)
    {
        return $candidature->getStatus() === Candidature::STATUS_PENDING && $candidature->getJobOffer()->getProject()->isTeamMember($user);
    }
}