<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users;

use App\Application\Exceptions\EntityNotFoundException;
use App\Application\Features\Users\UsersFacade;
use App\Domain\Entities\Users\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** @SuppressUnused */
#[IsGranted(User::ROLE_ADMIN, message: 'Forbidden')]
#[Route(path: '/api/admin/users/{userId}', name: 'api.admin.users.get', methods: [Request::METHOD_GET])]
class GetUserEndpoint extends AbstractController
{
    public function __construct(private readonly UsersFacade $usersFacade) {}

    /**
     * @throws EntityNotFoundException
     */
    public function __invoke(int $userId): Response
    {
        $user = $this->usersFacade->getById($userId);
        return new JsonResponse(UserResponse::fromEntity($user), Response::HTTP_OK);
    }
}
