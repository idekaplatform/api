<?php

namespace App\Controller\Project;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Manager\Project\ProjectManager;
use App\Manager\Project\JobOfferManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Security\Voter\ProjectVoter;

class JobOfferController extends AbstractController
{
    /**
     * @Route("/api/projects/{slug}/job-offers", name="create_project_job_offer", methods={"POST"})
     */
    public function createJobOffer(Request $request, string $slug, ProjectManager $projectManager, JobOfferManager $jobOfferManager)
    {
        if (($project = $projectManager->get($slug)) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::JOB_OFFER_CREATE, $project);

        return new JsonResponse($jobOfferManager->create($project, $request->request->all()), 201);
    }

    /**
     * @Route("/api/projects/{slug}/job-offers", name="get_project_job_offers", methods={"GET"})
     */
    public function getProjectJobOffers(string $slug, ProjectManager $projectManager, JobOfferManager $jobOfferManager)
    {
        if (($project = $projectManager->get($slug)) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        return new JsonResponse($jobOfferManager->getProjectJobOffers($project));
    }
}