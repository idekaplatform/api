<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Project\Project;
use App\Entity\User\User;
use App\Entity\Project\News;
use App\Utils\Slugger;

class NewsManager
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

    public function get(int $id): ?News
    {
        return $this->em->getRepository(News::class)->find($id);
    }

    public function getProjectNews(Project $project, User $user = null): array
    {
        $criterias = [ 'project' => $project ];
        if ($user === null || !$project->isTeamMember($user)) {
            $criterias['isPublished'] = true;
        }
        return $this->em->getRepository(News::class)->findBy($criterias);
    }

    public function create(array $data, Project $project, User $user): News
    {
        $news = (new News())
            ->setProject($project)
            ->setAuthor($user)
            ->setTitle($data['title'])
            ->setSlug($this->slugger->slugify($data['title']))
            ->setContent($data['content'])
        ;
        $this->em->persist($news);
        $this->em->flush();

        return $news;
    }

    public function publish(News $news)
    {
        $news->publish();

        $this->em->flush();
    }

    public function unpublish(News $news)
    {
        $news->unpublish();

        $this->em->flush();
    }
}