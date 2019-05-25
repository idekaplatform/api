<?php

namespace App\Controller\User;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    /**
     * @Route("/api/me", name="get_my_infos", methods={"GET"})
     */
    public function getMyInfos()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return new JsonResponse($this->getUser()); 
    }
}