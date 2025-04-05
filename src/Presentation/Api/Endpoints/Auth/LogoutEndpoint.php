<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Auth;

use App\Domain\Entities\Users\Events\UserLoggedOutEvent;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Responses\NoContentResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/** @SuppressUnused */
#[Route(path: '/api/auth/logout', name: 'api.auth.logout', methods: [Request::METHOD_POST])]
class LogoutEndpoint extends AbstractController
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher) {}

    public function __invoke(#[CurrentUser] ?User $user): Response
    {
        if ($user === null) {
            throw new UnauthorizedHttpException('Bearer realm="Access to logout endpoint"');
        }

        $this->eventDispatcher->dispatch(new UserLoggedOutEvent($user));

        return new NoContentResponse();
    }
}
