<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;
use App\Utils\Slugger;
use App\Entity\Project\Project;
use App\Entity\Project\JobOffer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ValidationException;

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
}