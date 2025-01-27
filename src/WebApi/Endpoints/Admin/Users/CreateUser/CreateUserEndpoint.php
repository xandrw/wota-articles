<?php

declare(strict_types=1);

namespace App\WebApi\Endpoints\Admin\Users\CreateUser;

use App\Application\Features\Admin\Users\CreateUserAction;
use App\Domain\Users\User;
use App\WebApi\Endpoints\Admin\Users\UserResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Throwable;

/** @SuppressUnused */
#[IsGranted(User::ROLE_ADMIN)]
#[Route(path: '/admin/users', name: 'api.admin.users.create', methods: [Request::METHOD_POST])]
class CreateUserEndpoint extends AbstractController
{
    public function __construct(private readonly CreateUserAction $createUserAction)
    {
    }

    /**
     * @throws Throwable Handled by one of the exception listeners
     */
    public function __invoke(#[MapRequestPayload] CreateUserRequest $request): Response
    {
        $user = $this->createUserAction->__invoke($request->email, $request->password, $request->roles);

        return new JsonResponse(UserResponse::fromEntity($user), Response::HTTP_CREATED);
    }
}
