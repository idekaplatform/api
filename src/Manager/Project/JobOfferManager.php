<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;
use App\Utils\Slugger;
use App\Entity\Project\Project;
use App\Entity\Project\JobOffer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ValidationException;
use App\Entity\Skill;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\Project\Skill as JobSkill;

class JobOfferManager
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var ValidatorInterface */
    protected $validator;
    /** @var Slugger */
    protected $slugger;

    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, Slugger $slugger)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->slugger = $slugger;
    }

    public function get(int $id): ?JobOffer
    {
        return $this->em->getRepository(JobOffer::class)->find($id);
    }

    public function getProjectJobOffers(Project $project): array
    {
        return $this->em->getRepository(JobOffer::class)->findByProject($project);
    }

    public function create(Project $project, array $data): JobOffer
    {
        $jobOffer =
            (new JobOffer())
            ->setTitle($data['title'])
            ->setSlug($this->slugger->slugify($data['title']))
            ->setContent($data['content'])
            ->setProject($project)
        ;
        if (count($errors = $this->validator->validate($jobOffer)) > 0) {
            throw new ValidationException($errors);
        }
        $this->em->persist($jobOffer);
        $this->em->flush();

        return $jobOffer;
    }

    public function addSkill(JobOffer $jobOffer, Skill $skill, int $level)
    {
        $jobSkill =
            (new JobSkill())
            ->setJobOffer($jobOffer)
            ->setSkill($skill)
            ->setLevel($level)
        ;
        $this->em->persist($jobSkill);
        $this->em->flush();

        return $jobSkill;
    }

    public function removeSkill(JobOffer $jobOffer, Skill $skill)
    {
        if (($jobOfferSkill = $jobOffer->findSkill($skill)) === null) {
            throw new NotFoundHttpException('projects.job_offers.skills.not_found');
        }
        $jobOffer->removeSkill($jobOfferSkill);
        $this->em->remove($jobOfferSkill);
        $this->em->flush();
    }

    public function updateSkillLevel(JobOffer $jobOffer, Skill $skill, int $level)
    {
        if (($jobOfferSkill = $jobOffer->findSkill($skill)) === null) {
            throw new NotFoundHttpException('projects.job_offers.skills.not_found');
        }
        $jobOfferSkill->setLevel($level);
        $this->em->flush();
    }
}