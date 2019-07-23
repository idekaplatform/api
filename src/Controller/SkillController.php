<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Manager\SkillManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class SkillController extends AbstractController
{
    /**
     * @Route("/api/skills", name="get_skills", methods={"GET"})
     */
    public function getAll(SkillManager $skillManager)
    {
        return new JsonResponse($skillManager->getAll());
    }
}