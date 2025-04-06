<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users\UpdateUserRoles;

use App\Application\Features\Admin\Users\UpdateUserRoleInvoker;
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
#[Route(
    path: '/api/admin/users/{userId}/roles',
    name: 'api.admin.users.roles.update',
    methods: [Request::METHOD_POST]
)]
class UpdateUserRolesEndpoint extends AbstractController
{
    public function __construct(private readonly UpdateUserRoleInvoker $updateRoleInvoker) {}

    public function __invoke(int $userId, #[MapRequestPayload] UpdateUserRolesRequest $request): Response
    {
        $user = $this->updateRoleInvoker->__invoke($userId, $request->roles);
        return new JsonResponse(UserResponse::fromEntity($user));
    }
}
