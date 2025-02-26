<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Auth\ChangePassword;

use App\Application\Features\Auth\ChangePasswordInvoker;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Responses\NoContentResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/** @SuppressUnused */
#[Route(path: '/api/auth/change-password', name: 'api.auth.changePassword', methods: [Request::METHOD_POST])]
class ChangePasswordEndpoint extends AbstractController
{
    public function __construct(private readonly ChangePasswordInvoker $changePasswordInvoker)
    {
    }

    public function __invoke(#[MapRequestPayload] ChangePasswordRequest $request, #[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            throw new UnauthorizedHttpException('Bearer realm="Access to change password endpoint"');
        }

        // TODO: peer review __invoke() call instead of this notation
        ($this->changePasswordInvoker)($user->getEmail(), $request->oldPassword, $request->password);

        return new NoContentResponse();
    }
}
