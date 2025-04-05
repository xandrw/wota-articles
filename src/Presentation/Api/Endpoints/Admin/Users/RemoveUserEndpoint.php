<?php

declare(strict_types=1);

namespace App\Presentation\Api\Endpoints\Admin\Users;

use App\Application\Exceptions\EntityNotFoundException;
use App\Application\Features\Admin\Users\RemoveUserInvoker;
use App\Domain\Entities\Users\User;
use App\Presentation\Api\Responses\NoContentResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/** @SuppressUnused */
#[IsGranted(User::ROLE_ADMIN, message: 'Forbidden')]
#[Route(path: '/api/admin/users/{userId}', name: 'api.admin.users.remove', methods: [Request::METHOD_DELETE])]
class RemoveUserEndpoint extends AbstractController
{
    public function __construct(private readonly RemoveUserInvoker $removeUserInvoker) {}

    /**
     * @throws EntityNotFoundException Handled by exception listener
     */
    public function __invoke(int $userId): Response
    {
        $this->removeUserInvoker->__invoke($userId);
        return new NoContentResponse();
    }
}
