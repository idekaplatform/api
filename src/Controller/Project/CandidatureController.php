<?php

namespace App\Controller\Project;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Manager\Project\JobOfferManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use App\Security\Voter\Project\CandidatureVoter;
use App\Manager\Project\CandidatureManager;

class CandidatureController extends AbstractController
{
    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/candidatures", name="get_project_job_offer_candidatures", methods={"GET"})
     */
    public function getJobOfferCandidatures(JobOfferManager $jobOfferManager, CandidatureManager $candidatureManager, int $id)
    {
        if (($jobOffer = $jobOfferManager->get($id)) === null) {
            throw new NotFoundHttpException('projects.job_offers.not_found');
        }
        return new JsonResponse($candidatureManager->getPendingCandidatures($jobOffer));
    }

    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/candidatures/{cId}", name="get_project_job_offer_candidature", methods={"GET"})
     */
    public function getCandidature(CandidatureManager $candidatureManager, int $cId)
    {
        if (($candidature = $candidatureManager->get($cId)) === null) {
            throw new NotFoundHttpException('projects.job_offers.candidatures.not_found');
        }
        return new JsonResponse($candidature);
    }

    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/candidatures", name="add_project_job_offer_candidature", methods={"POST"})
     */
    public function addCandidature(Request $request, JobOfferManager $jobOfferManager, CandidatureManager $candidatureManager, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (($jobOffer = $jobOfferManager->get($id)) === null) {
            throw new NotFoundHttpException('projects.job_offers.not_found');
        }
        return new JsonResponse($candidatureManager->create($jobOffer, $this->getUser(), $request->request->get('message')), 201);
    }

    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/candidatures/{cId}", name="cancel_project_job_offer_candidature", methods={"DELETE"})
     */
    public function cancelCandidature(CandidatureManager $candidatureManager, int $cId)
    {
        if (($candidature = $candidatureManager->get($cId)) === null) {
            throw new NotFoundHttpException('projects.job_offers.candidatures.not_found');
        }
        $this->denyAccessUnlessGranted(CandidatureVoter::CANCEL, $candidature);
        $candidatureManager->cancel($candidature);
        return new Response('', 204);
    }

    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/candidatures/{cId}/accept", name="accept_project_job_offer_candidature", methods={"POST"})
     */
    public function acceptCandidature(CandidatureManager $candidatureManager, int $cId)
    {
        if (($candidature = $candidatureManager->get($cId)) === null) {
            throw new NotFoundHttpException('projects.job_offers.candidatures.not_found');
        }
        $this->denyAccessUnlessGranted(CandidatureVoter::RESPOND, $candidature);
        $candidatureManager->accept($candidature, $this->getUser());
        return new Response('', 204);
    }

    /**
     * @Route("/api/projects/{slug}/job-offers/{id}/candidatures/{cId}/decline", name="decline_project_job_offer_candidature", methods={"POST"})
     */
    public function declineCandidature(CandidatureManager $candidatureManager, int $cId)
    {
        if (($candidature = $candidatureManager->get($cId)) === null) {
            throw new NotFoundHttpException('projects.job_offers.candidatures.not_found');
        }
        $this->denyAccessUnlessGranted(CandidatureVoter::RESPOND, $candidature);
        $candidatureManager->decline($candidature, $this->getUser());
        return new Response('', 204);
    }
}