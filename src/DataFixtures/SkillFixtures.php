<?php

namespace App\DataFixtures;

use App\Entity\Skill;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public function load(ObjectManager $om)
    {
        foreach ($this->getSkills() as $skill) {
            $om->persist($skill);
            $this->addReference("skill-{$skill->getName()}", $skill);
        }
        $om->flush();
    }

    protected function getSkills()
    {
        // Management
        yield (new Skill())->setName('scrum')->setType(Skill::TYPE_MANAGEMENT);
        yield (new Skill())->setName('kanban')->setType(Skill::TYPE_MANAGEMENT);
        yield (new Skill())->setName('waterfall')->setType(Skill::TYPE_MANAGEMENT);
        yield (new Skill())->setName('product_design')->setType(Skill::TYPE_MANAGEMENT);
        yield (new Skill())->setName('product_management')->setType(Skill::TYPE_MANAGEMENT);
        // Programming
        yield (new Skill())->setName('php')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('symfony')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('laravel')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('zend')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('codeigniter')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('cakephp')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('javascript')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('vuejs')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('react')->setType(Skill::TYPE_PROGRAMMING);
        yield (new Skill())->setName('angular')->setType(Skill::TYPE_PROGRAMMING);
        // Infrastructure
        yield (new Skill())->setName('docker')->setType(Skill::TYPE_INFRASTRUCTURE);
        yield (new Skill())->setName('ansible')->setType(Skill::TYPE_INFRASTRUCTURE);
        yield (new Skill())->setName('terraform')->setType(Skill::TYPE_INFRASTRUCTURE);
        yield (new Skill())->setName('kubernetes')->setType(Skill::TYPE_INFRASTRUCTURE);
    }
}