<?php

declare(strict_types=1);

namespace App\Application\Features\Users;

use App\Application\Exceptions\DuplicateEntityException;
use App\Application\Exceptions\EntityNotFoundException;
use App\Application\Exceptions\UnauthorizedException;
use App\Application\Results\PaginatedResult;
use App\Domain\Entities\Users\AccessToken;
use App\Domain\Entities\Users\User;
use Exception;
use SensitiveParameter;

readonly class UsersFacade
{
    public function __construct(
        private LoginInvoker $loginInvoker,
        private DeleteUserTokensInvoker $deleteUserTokensInvoker,
        private CreateUserInvoker $createUserInvoker,
        private UpdateUserInvoker $updateUserInvoker,
        private RemoveUserInvoker $removeUserInvoker,
        private GetUserByIdInvoker $getUserByIdInvoker,
        private ListPaginatedUsersInvoker $listPaginatedUsersInvoker,
    ) {}

    /**
     * @throws UnauthorizedException
     */
    public function login(string $email, #[SensitiveParameter] string $password): AccessToken
    {
        return $this->loginInvoker->__invoke($email, $password);
    }

    public function deleteAccessTokens(User $user): void
    {
        $this->deleteUserTokensInvoker->__invoke($user);
    }

    /**
     * @throws DuplicateEntityException
     */
    public function create(string $email, #[SensitiveParameter] string $password, array $roles): User
    {
        return $this->createUserInvoker->__invoke($email, $password, $roles);
    }

    /**
     * @throws DuplicateEntityException
     */
    public function update(
        User $user,
        string $email,
        #[SensitiveParameter] string $password,
        ?array $roles = null,
    ): User
    {
        return $this->updateUserInvoker->__invoke($user, $email, $password, $roles);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function remove(int $userId): void
    {
        $this->removeUserInvoker->__invoke($userId);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getById(int $userId): User
    {
        return $this->getUserByIdInvoker->__invoke($userId);
    }

    /**
     * @throws Exception
     */
    public function listPaginated(int $pageNumber, int $pageSize): PaginatedResult
    {
        return $this->listPaginatedUsersInvoker->__invoke($pageNumber, $pageSize);
    }
}
