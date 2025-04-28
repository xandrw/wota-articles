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
use Psr\Log\LoggerInterface;
use SensitiveParameter;

readonly class UsersFacade
{
    public function __construct(
        private LoginTask $loginTask,
        private DeleteAccessTokensTask $deleteAccessTokensTask,
        private CreateUserTask $createUserTask,
        private UpdateUserTask $updateUserTask,
        private RemoveUserTask $removeUserTask,
        private GetUserByIdTask $getUserByIdTask,
        private ListPaginatedUsersTask $listPaginatedUsersTask,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws UnauthorizedException
     */
    public function login(string $email, #[SensitiveParameter] string $password): AccessToken
    {
        try {
            return $this->loginTask->__invoke($email, $password);
        } catch (Exception $e) {
            $caller = self::class;
            $callerMethod = __METHOD__;
            $this->logger->error("$caller::$callerMethod", [
                'params' => base64_encode(serialize(func_get_args())), // WIP simulate encrypted data
                'exception' => $e::class,
                'exceptionMessage' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function deleteAccessTokens(User $user): void
    {
        $this->deleteAccessTokensTask->__invoke($user);
    }

    /**
     * @throws DuplicateEntityException
     */
    public function create(string $email, #[SensitiveParameter] string $password, array $roles): User
    {
        return $this->createUserTask->__invoke($email, $password, $roles);
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
        return $this->updateUserTask->__invoke($user, $email, $password, $roles);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function remove(int $userId): void
    {
        $this->removeUserTask->__invoke($userId);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getById(int $userId): User
    {
        return $this->getUserByIdTask->__invoke($userId);
    }

    /**
     * @throws Exception
     */
    public function listPaginated(int $pageNumber, int $pageSize): PaginatedResult
    {
        return $this->listPaginatedUsersTask->__invoke($pageNumber, $pageSize);
    }
}
