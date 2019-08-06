<?php

namespace App\Security\Voter\Project;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use App\Entity\Project\Project;
use App\Entity\Project\News;
use App\Entity\User\User;

class NewsVoter extends Voter
{
    const CREATE = 'news_create';
    const UPDATE = 'news_update';
    const PUBLISH = 'news_publish';
    const DELETE = 'news_delete';

    public function supports($attribute, $subject)
    {
        if ($subject instanceof Project && in_array($attribute, [ self::CREATE ])) {
            return true;
        }
        if ($subject instanceof News && in_array($attribute, [ self::UPDATE, self::PUBLISH, self::DELETE ])) {
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
                return $this->canCreateNews($subject, $user);
            case self::UPDATE:
                return $this->canUpdateNews($subject, $user);
            case self::PUBLISH:
                return $this->canPublishNews($subject, $user);
            case self::DELETE:
                return $this->canDeleteNews($subject, $user);
        }
        throw new \LogicException('This code should not be reached');
    }

    protected function canCreateNews(Project $project, User $user): bool
    {
        return $project->isTeamMember($user);
    }

    protected function canUpdateNews(News $news, User $user): bool
    {
        return $news->getAuthor() === $user;
    }

    protected function canPublishNews(News $news, User $user): bool
    {
        return $news->getProject()->isTeamMember($user);
    }

    protected function canDeleteNews(News $news, User $user): bool
    {
        return $news->getProject()->isTeamMember($user);
    }
}