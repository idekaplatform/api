<?php

namespace App\Controller\Project;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Manager\Project\ProjectManager;
use App\Manager\Project\JobOfferManager;
use App\Manager\SkillManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Security\Voter\ProjectVoter;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/skills", name="add_project_job_offer_skill", methods={"POST"})
     */
    public function addJobOfferSkill(Request $request, JobOfferManager $jobOfferManager, SkillManager $skillManager)
    {
        if (($jobOffer = $jobOfferManager->get($request->attributes->get('id'))) === null) {
            throw new NotFoundHttpException('projects.job_offers.not_found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::JOB_OFFER_ADD_SKILL, $jobOffer);
        if (($skill = $skillManager->get($request->request->get('skill_id'))) === null) {
            throw new NotFoundHttpException('skills.not_found');
        }
        return new JsonResponse($jobOfferManager->addSkill($jobOffer, $skill, $request->request->get('level')), 201);
    }

    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/skills/{skill_id}", name="remove_project_job_offer_skill", methods={"DELETE"})
     */
    public function removeJobOfferSkill(Request $request, JobOfferManager $jobOfferManager, SkillManager $skillManager)
    {
        if (($jobOffer = $jobOfferManager->get($request->attributes->get('id'))) === null) {
            throw new NotFoundHttpException('projects.job_offers.not_found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::JOB_OFFER_REMOVE_SKILL, $jobOffer);
        if (($skill = $skillManager->get($request->attributes->get('skill_id'))) === null) {
            throw new NotFoundHttpException('skills.not_found');
        }
        $jobOfferManager->removeSkill($jobOffer, $skill);
        return new Response('', 204);
    }
}