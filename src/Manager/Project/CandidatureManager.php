<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Entity\Project\JobOffer;
use App\Entity\User\User;
use App\Entity\Project\Candidature;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CandidatureManager
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var EventDispatcherInterface */
    protected $eventDispatcher;
    /** @var MemberManager */
    protected $memberManager;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, MemberManager $memberManager)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->memberManager = $memberManager;
    }

    public function get(int $id)
    {
        return $this->em->getRepository(Candidature::class)->find($id);
    }

    public function getPendingCandidatures(JobOffer $jobOffer)
    {
        return $this->em->getRepository(Candidature::class)->findBy([
            'jobOffer' => $jobOffer,
            'status' => Candidature::STATUS_PENDING
        ]);
    }

    public function isAlreadyCandidate(JobOffer $jobOffer, User $user): bool
    {
        return $this->em->getRepository(Candidature::class)->findOneBy([
            'jobOffer' => $jobOffer,
            'user' => $user
        ]) !== null;
    }

    public function create(JobOffer $jobOffer, User $user, string $message = null): Candidature
    {
        if (($this->isAlreadyCandidate($jobOffer, $user))) {
            throw new BadRequestHttpException('projects.job_offers.candidatures.already_candidate');
        }
        $candidature =
            (new Candidature())
            ->setJobOffer($jobOffer)
            ->setUser($user)
            ->setMessage($message)
        ;
        $this->em->persist($candidature);
        $this->em->flush();
        return $candidature;
    }

    public function cancel(Candidature $candidature)
    {
        $candidature->setStatus(Candidature::STATUS_CANCELLED);

        $this->em->flush();
    }

    public function accept(Candidature $candidature, User $user)
    {
        $candidature
            ->setStatus(Candidature::STATUS_ACCEPTED)
            ->setResponder($user)
            ->setRespondedAt(new \DateTime())
        ;
        $candidature->getJobOffer()->activate(false);

        $this->em->flush();

        $this->memberManager->create($candidature->getJobOffer()->getProject(), $candidature->getUser());
    }

    public function decline(Candidature $candidature, User $user)
    {
        $candidature
            ->setStatus(Candidature::STATUS_DECLINED)
            ->setResponder($user)
            ->setRespondedAt(new \DateTime())
        ;
        $this->em->flush();
    }
}