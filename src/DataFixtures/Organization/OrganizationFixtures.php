<?php

namespace App\DataFixtures\Organization;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\User\UserFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Organization\Organization;
use App\Entity\Organization\SocialNetwork;
use App\Entity\Organization\Member;

class OrganizationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $om)
    {
        foreach ($this->getOrganizations() as $organization) {
            $om->persist($organization);
            $this->addReference("organization-{$organization->getId()}", $organization);
        }
        $om->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

    protected function getOrganizations()
    {
        $organization = new Organization();
        yield $organization
            ->setId(1)
            ->setName('New Talents France')
            ->setSlug('new-talents-france')
            ->setShortDescription('Une association merveilleuse')
            ->setDescription('Développons nos projets et nos compétences ensemble !')
            ->setWebsiteUrl('http://new-talents.fr/')
            ->addSocialNetwork(
                (new SocialNetwork())
                ->setUrl('https://www.facebook.com/NewTalentsFrance')
                ->setNetwork(SocialNetwork::NETWORK_FACEBOOK)
                ->setOrganization($organization)
            )
            ->addSocialNetwork(
                (new SocialNetwork())
                ->setUrl('https://twitter.com/NewTalentsFR')
                ->setNetwork(SocialNetwork::NETWORK_TWITTER)
                ->setOrganization($organization)
            )
            ->addSocialNetwork(
                (new SocialNetwork())
                ->setUrl('https://discord.gg/sFew2ek')
                ->setNetwork(SocialNetwork::NETWORK_DISCORD)
                ->setOrganization($organization)
            )
            ->addMember(
                (new Member())
                ->setUser($this->getReference('user-1'))
                ->setOrganization($organization)
            )
        ;
    }
}