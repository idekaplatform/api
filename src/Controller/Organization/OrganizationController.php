<?php

namespace App\Controller\Organization;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Manager\User\UserManager;
use App\Manager\Organization\OrganizationManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrganizationController extends AbstractController
{
    /**
     * @Route("/api/users/{id}/organizations", name="get_user_organizations", methods={"GET"})
     */
    public function getUserOrganizations(int $id, UserManager $userManager, OrganizationManager $organizationManager)
    {
        if (($user = $userManager->get($id)) === null) {
            throw new NotFoundHttpException('users.not_found');
        }
        return new JsonResponse($organizationManager->getMemberOrganizations($user));
    }

    /**
     * @Route("/api/organizations/{slug}", name="get_organization", methods={"GET"})
     */
    public function getOrganization(string $slug, OrganizationManager $organizationManager)
    {
        if (($organization = $organizationManager->get($slug)) === null) {
            throw new NotFoundHttpException('organizations.not_found');
        }
        return new JsonResponse($organization);
    }
}