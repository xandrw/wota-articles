<?php

declare(strict_types=1);

namespace App\WebApi\Endpoints\Auth\Login;

use App\Application\Features\Auth\LoginAction;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/** @SuppressUnused */
#[Route(path: '/login', name: 'api.auth.login', methods: [Request::METHOD_POST])]
class LoginEndpoint extends AbstractController
{
    public function __construct(private readonly LoginAction $loginAction)
    {
    }

    /**
     * @throws RandomException
     */
    public function __invoke(#[MapRequestPayload] LoginRequest $request): Response
    {
        $accessToken = $this->loginAction->__invoke($request->email, $request->password);

        return new JsonResponse(LoginResponse::fromEntity($accessToken));
    }
}
