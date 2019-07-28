<?php

namespace App\DataFixtures\Project;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Utils\Slugger;
use App\Entity\Project\JobOffer;
use App\Entity\Project\Skill;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class JobOfferFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var Slugger */
    protected $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $om)
    {
        foreach ($this->getJobOffers() as $jobOffer) {
            $om->persist($jobOffer);
            $this->addReference("job-offer-{$jobOffer->getSlug()}", $jobOffer);
        }
        $om->flush();
    }

    protected function getJobOffers()
    {
        $jobOffer = (new JobOffer())
            ->setProject($this->getReference('project-ideka'))
            ->setTitle('Développeur Symfony')
            ->setSlug($this->slugger->slugify('Développeur Symfony'))
            ->setContent('Un dev junior est tout à fait accepté pour venir apprendre à faire du Symfony sur le projet !')
        ;
        $jobOffer->addSkill((new Skill())
            ->setJobOffer($jobOffer)
            ->setSkill($this->getReference('skill-symfony'))
            ->setLevel(6)
        );
        $jobOffer->addSkill((new Skill())
            ->setJobOffer($jobOffer)
            ->setSkill($this->getReference('skill-docker'))
            ->setLevel(5)
        );
        $jobOffer->addSkill((new Skill())
            ->setJobOffer($jobOffer)
            ->setSkill($this->getReference('skill-git'))
            ->setLevel(6)
        );
        yield $jobOffer;
    }

    public function getDependencies()
    {
        return [
            ProjectFixtures::class
        ];
    }
}