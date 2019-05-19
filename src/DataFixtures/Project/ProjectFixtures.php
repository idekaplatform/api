<?php

namespace App\DataFixtures\Project;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\Organization\OrganizationFixtures;
use App\Entity\Project\Project;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Project\SocialNetwork;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $om)
    {
        foreach ($this->getProjects() as $project) {
            $project->publish();
            $om->persist($project);
            $this->addReference("project-{$project->getId()}", $project);
        }
        $om->flush();
    }

    public function getDependencies()
    {
        return [
            OrganizationFixtures::class,
        ];
    }

    protected function getProjects()
    {
        $project = new Project();
        yield $project
            ->setId(1)
            ->setName('Ideka')
            ->setSlug('ideka')
            ->setShortDescription('Une plateforme pour les développer tous')
            ->setDescription('Une plateforme pour les référencer, et dans les ténèbres les faire gagner')
            ->setWebsiteUrl('https://ideka.app')
            ->setOrganization($this->getReference('organization-1'))
            ->addSocialNetwork(
                (new SocialNetwork())
                ->setUrl('https://gitlab.com/idekaplatform/')
                ->setNetwork(SocialNetwork::NETWORK_GITLAB)
                ->setProject($project)
            )
        ;
    }
}