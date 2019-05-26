<?php

namespace App\Manager\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserManager
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function get(int $id): ?User
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function getByUsername(string $username): ?User
    {
        return $this->em->getRepository(User::class)->findOneByUsername($username);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneByEmail($email);
    }

    public function create(string $username, string $email, string $password): User
    {
        if ($this->getByUsername($username) !== null) {
            throw new BadRequestHttpException('users.already_token_username');
        }
        if ($this->getByEmail($email) !== null) {
            throw new BadRequestHttpException('users.already_taken_email');
        }
        $user = (new User())
            ->setUsername($username)
            ->setEmail($email)
            ->activate(true)
            ->setRoles(['ROLE_USER'])
        ;
        $user->setPassword($this->encoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}