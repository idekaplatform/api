<?php

namespace App\Controller\Project;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Manager\Project\ProjectManager;
use App\Manager\Project\NewsManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Security\Voter\ProjectVoter;

class NewsController extends AbstractController
{
    /**
     * @Route("/api/projects/{slug}/news", name="get_project_news", methods={"GET"})
     */
    public function getProjectNews(string $slug, ProjectManager $projectManager, NewsManager $newsManager)
    {
        if (($project = $projectManager->get($slug)) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        return new JsonResponse($newsManager->getProjectNews($project, $this->getUser()));
    }

    /**
     * @Route("/api/projects/{slug}/news", name="create_project_news", methods={"POST"})
     */
    public function create(Request $request, ProjectManager $projectManager, NewsManager $newsManager)
    {
        if (($project = $projectManager->get($request->attributes->get('slug'))) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::NEWS_CREATE, $project);

        return new JsonResponse($newsManager->create($request->request->all(), $project, $this->getUser()), 201);
    }

    /**
     * @Route("/api/projects/{slug}/news/{id}/publish", name="publish_project_news", methods={"PATCH"})
     */
    public function publish(int $id, NewsManager $newsManager)
    {
        if (($news = $newsManager->get($id)) === null) {
            throw new NotFoundHttpException('news.not_found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::NEWS_PUBLISH, $news);

        $newsManager->publish($news);

        return new Response('', 204);
    }

    /**
     * @Route("/api/projects/{slug}/news/{id}/unpublish", name="unpublish_project_news", methods={"PATCH"})
     */
    public function unpublish(int $id, NewsManager $newsManager)
    {
        if (($news = $newsManager->get($id)) === null) {
            throw new NotFoundHttpException('news.not_found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::NEWS_PUBLISH, $news);

        $newsManager->unpublish($news);

        return new Response('', 204);
    }
}