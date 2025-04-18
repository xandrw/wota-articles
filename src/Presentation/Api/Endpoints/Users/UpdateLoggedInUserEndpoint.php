<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Users;

use App\Application\Features\Users\UpdateUserInvoker;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Endpoints\Admin\Users\UserResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/** @SuppressUnused */
#[Route(path: '/api/auth/user', name: 'api.auth.user.update', methods: [Request::METHOD_POST])]
class UpdateLoggedInUserEndpoint extends AbstractController
{
    public function __construct(private readonly UpdateUserInvoker $updateUserInvoker) {}

    public function __invoke(#[MapRequestPayload] UpdateUserRequest $request, #[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            throw new UnauthorizedHttpException('Bearer realm="Access to change password endpoint"');
        }

        $this->updateUserInvoker->__invoke($user, $request->email, $request->oldPassword, $request->newPassword);

        return new JsonResponse(UserResponse::fromEntity($user), Response::HTTP_CREATED);
    }
}
