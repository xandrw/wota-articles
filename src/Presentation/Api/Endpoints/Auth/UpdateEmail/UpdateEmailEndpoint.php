<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Auth\UpdateEmail;

use App\Application\Features\Auth\UpdateEmailInvoker;
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
#[Route(path: '/api/auth/email', name: 'api.auth.updateEmail', methods: [Request::METHOD_POST])]
class UpdateEmailEndpoint extends AbstractController
{
    public function __construct(private readonly UpdateEmailInvoker $updateEmailInvoker) {}

    public function __invoke(#[MapRequestPayload] UpdateEmailRequest $request, #[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            throw new UnauthorizedHttpException('Bearer realm="Access to change password endpoint"');
        }

        if ($request->email === $user->getEmail()) {
            return new NoContentResponse();
        }

        $this->updateEmailInvoker->__invoke($user, $request->email);
        return new NoContentResponse();
    }
}
