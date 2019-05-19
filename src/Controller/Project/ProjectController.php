<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Manager\Project\ProjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/api/projects", name="get_projects", methods={"GET"})
     */
    public function getAll(ProjectManager $projectManager)
    {
        return new JsonResponse($projectManager->getAll());
    }

    /**
     * @Route("/api/projects/{slug}", name="get_project", methods={"GET"})
     */
    public function getProject(ProjectManager $projectManager, string $slug)
    {
        if (($project = $projectManager->get($slug)) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        return new JsonResponse($project);
    }
}