<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Users;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Features\Users\UsersFacade;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Endpoints\Admin\Users\UserResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/** @SuppressUnused */
#[Route(path: '/api/auth/user', name: 'api.auth.user.update', methods: [Request::METHOD_POST])]
class UpdateLoggedInUserEndpoint extends AbstractController
{
    public function __construct(
        private readonly UsersFacade $usersFacade,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    /**
     * @throws DuplicateEntityException
     * @throws UnauthorizedHttpException
     */
    public function __invoke(
        #[MapRequestPayload] UpdateLoggedInUserRequest $request,
        #[CurrentUser] ?User $user,
    ): Response
    {
        if ($user === null) {
            throw new UnauthorizedHttpException('Bearer realm="[UpdateLoggedInUserEndpoint] User not authenticated"');
        }

        if ($request->email === null && $request->newPassword === null) {
            return new JsonResponse(UserResponse::fromEntity($user), Response::HTTP_OK);
        }

        if ($user->validatePassword($request->oldPassword, $this->passwordHasher) === false) {
            throw new UnauthorizedHttpException('Bearer realm="[UpdateLoggedInUserEndpoint] Old password not valid"');
        }

        $this->usersFacade->update(
            $user,
            $request->email ?? $user->getEmail(),
            $request->newPassword ?? $request->oldPassword,
        );

        return new JsonResponse(UserResponse::fromEntity($user), Response::HTTP_OK);
    }
}
