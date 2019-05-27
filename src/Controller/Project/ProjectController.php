<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Manager\User\UserManager;
use App\Manager\Project\ProjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/api/users/{id}/projects", name="get_user_projects", methods={"GET"})
     */
    public function getUserProjects(int $id, UserManager $userManager, ProjectManager $projectManager)
    {
        if (($user = $userManager->get($id)) === null) {
            throw new NotFoundHttpException('users.not_found');
        }
        return new JsonResponse($projectManager->getUserProjects($user));
    }

    /**
     * @Route("/api/projects", name="create_project", methods={"POST"})
     */
    public function createProject(Request $request, ProjectManager $projectManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return new JsonResponse($projectManager->create($request->request->all(), $this->getUser()), 201);
    }
}