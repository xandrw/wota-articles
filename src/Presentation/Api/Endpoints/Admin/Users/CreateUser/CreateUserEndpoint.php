<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users\CreateUser;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Features\Users\UsersFacade;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Endpoints\Admin\Users\UserResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** @SuppressUnused */
#[IsGranted(User::ROLE_ADMIN, message: 'Forbidden')]
#[Route(path: '/api/admin/users', name: 'api.admin.users.create', methods: [Request::METHOD_POST])]
class CreateUserEndpoint extends AbstractController
{
    public function __construct(private readonly UsersFacade $usersFacade) {}

    /**
     * @throws DuplicateEntityException
     */
    public function __invoke(#[MapRequestPayload] CreateUserRequest $request): Response
    {
        $user = $this->usersFacade->create($request->email, $request->password, $request->roles);
        return new JsonResponse(UserResponse::fromEntity($user), Response::HTTP_CREATED);
    }
}
