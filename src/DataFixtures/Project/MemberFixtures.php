<?php

namespace App\DataFixtures\Project;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Project\Member;

class MemberFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $om)
    {
        foreach ($this->getMembers() as $member) {
            $om->persist($member);
        }
        $om->flush();
    }

    public function getMembers()
    {
        yield (new Member())
            ->setUser($this->getReference('user-1'))
            ->setProject($this->getReference('project-ideka'))
        ;
    }

    public function getDependencies()
    {
        return [
            ProjectFixtures::class
        ];
    }
}