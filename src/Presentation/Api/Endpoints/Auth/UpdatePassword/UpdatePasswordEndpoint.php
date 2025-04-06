<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Auth\UpdatePassword;

use App\Application\Features\Auth\UpdatePasswordInvoker;
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
#[Route(path: '/api/auth/password', name: 'api.auth.updatePassword', methods: [Request::METHOD_POST])]
class UpdatePasswordEndpoint extends AbstractController
{
    public function __construct(private readonly UpdatePasswordInvoker $updatePasswordInvoker) {}

    public function __invoke(#[MapRequestPayload] UpdatePasswordRequest $request, #[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            throw new UnauthorizedHttpException('Bearer realm="Access to change password endpoint"');
        }

        $this->updatePasswordInvoker->__invoke($user->getEmail(), $request->oldPassword, $request->password);

        return new NoContentResponse();
    }
}
