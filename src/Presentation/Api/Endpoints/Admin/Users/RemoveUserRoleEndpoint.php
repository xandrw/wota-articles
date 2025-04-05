<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users;

use App\Application\Features\Admin\Users\RemoveUserRoleInvoker;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Responses\NoContentResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/** @SuppressUnused */
#[IsGranted(User::ROLE_ADMIN, message: 'Forbidden')]
#[Route(
    path: '/api/admin/users/{userId}/roles/{role}',
    name: 'api.admin.users.role.remove',
    methods: [Request::METHOD_DELETE]
)]
class RemoveUserRoleEndpoint extends AbstractController
{
    public function __construct(private readonly RemoveUserRoleInvoker $removeUserRoleInvoker) {}

    /**
     * @throws Throwable Handled by exception listener
     */
    public function __invoke(int $userId, string $role): Response
    {
        $user = $this->removeUserRoleInvoker->__invoke($userId, $role);
        return new NoContentResponse();
    }
}
