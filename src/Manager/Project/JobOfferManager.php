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
use App\Entity\Project\Skill as AppSkill;

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

    public function getProjectJobOffers(Project $project)
    {
        return $this->em->getRepository(JobOffer::class)->findByProject($project);
    }

    public function create(Project $project, array $data)
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
        foreach ($jobOffer->getSkills() as $jobSkill) {
            if ($jobSkill->getSkill() === $skill) {
                $jobOffer->removeSkill($jobSkill);
                $this->em->remove($jobSkill);
                $this->em->flush();
                return;
            }
        }
        throw new NotFoundHttpException('projects.job_offers.skills.not_found');
    }
}