<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users;

use App\Application\Features\Users\UsersFacade;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Responses\PaginatedResponse;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** @SuppressUnused */
#[IsGranted(User::ROLE_ADMIN, message: 'Forbidden')]
#[Route(path: '/api/admin/users', name: 'api.admin.users.list', methods: [Request::METHOD_GET])]
class ListUsersEndpoint extends AbstractController
{
    public function __construct(private readonly UsersFacade $usersFacade) {}

    /**
     * @throws Exception
     */
    public function __invoke(#[MapQueryParameter] int $page = 1, #[MapQueryParameter] int $limit = 10): Response
    {
        $paginatedUsers = $this->usersFacade->listPaginated($page, $limit);
        $users = [];

        foreach ($paginatedUsers->items as $user) {
            $users[] = UserResponse::fromEntity($user);
        }

        return new PaginatedResponse($users);
    }
}
