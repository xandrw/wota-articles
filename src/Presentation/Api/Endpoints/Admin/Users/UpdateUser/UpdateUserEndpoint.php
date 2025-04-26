<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users\UpdateUser;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Exceptions\EntityNotFoundException;
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
use Throwable;

/** @SuppressUnused */
#[IsGranted(User::ROLE_ADMIN, message: 'Forbidden')]
#[Route(path: '/api/admin/users/:userId', name: 'api.admin.users.update', methods: [Request::METHOD_PUT])]
class UpdateUserEndpoint extends AbstractController
{
    public function __construct(private readonly UsersFacade $usersFacade) {}

    /**
     * @throws EntityNotFoundException
     * @throws DuplicateEntityException
     */
    public function __invoke(#[MapRequestPayload] UpdateUserRequest $request, int $userId): Response
    {
        $user = $this->usersFacade->getById($userId);
        $this->usersFacade->update($user, $request->email, $request->password, $request->roles);
        return new JsonResponse(UserResponse::fromEntity($user), Response::HTTP_CREATED);
    }
}
