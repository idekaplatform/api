<?php

namespace App\DataFixtures\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $om)
    {
        foreach ($this->getUsers() as $user) {
            $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
            $om->persist($user);
            $this->addReference("user-{$user->getId()}", $user);
        }
        $om->flush();
    }

    protected function getUsers()
    {
        yield (new User())
            ->setId(1)
            ->setUsername('Admin')
            ->setEmail('admin@example.org')
            ->setPassword('admin')
            ->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_USER'])
            ->activate(true)
        ;
        yield (new User())
            ->setId(2)
            ->setUsername('Tester')
            ->setEmail('test@example.org')
            ->setPassword('test')
            ->setRoles(['ROLE_USER'])
            ->activate(true)
        ;
    }
}