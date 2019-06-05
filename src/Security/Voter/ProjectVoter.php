<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use App\Entity\Project\Project;
use App\Entity\Project\News;
use App\Entity\User\User;

class ProjectVoter extends Voter
{
    const PROJECT_UPDATE = 'update';
    const PROJECT_DELETE = 'delete';

    const NEWS_CREATE = 'news_create';
    const NEWS_UPDATE = 'news_update';
    const NEWS_PUBLISH = 'news_publish';
    const NEWS_DELETE = 'news_delete';

    public function supports($attribute, $subject)
    {
        if ($subject instanceof Project && in_array($attribute, [ self::PROJECT_UPDATE, self::PROJECT_DELETE, self::NEWS_CREATE ])) {
            return true;
        }
        if ($subject instanceof News && in_array($attribute, [ self::NEWS_UPDATE, self::NEWS_PUBLISH, self::NEWS_DELETE ])) {
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
            case self::PROJECT_UPDATE:
                return $this->canUpdateProject($subject, $user);
            case self::PROJECT_DELETE:
                return $this->canDeleteProject($subject, $user);
            case self::NEWS_CREATE:
                return $this->canCreateNews($subject, $user);
            case self::NEWS_UPDATE:
                return $this->canUpdateNews($subject, $user);
            case self::NEWS_PUBLISH:
                return $this->canPublishNews($subject, $user);
            case self::NEWS_DELETE:
                return $this->canDeleteNews($subject, $user);
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