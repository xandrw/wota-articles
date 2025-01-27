<?php

namespace App\Domain\Users;

use App\Domain\Contract;
use App\Domain\EntityInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SensitiveParameter;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity, ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
    public const string ROLE_USER = 'ROLE_USER';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';
    public const array ROLES = [self::ROLE_USER, self::ROLE_ADMIN];

    #[ORM\Id, ORM\Column(type: Types::INTEGER), ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING)]
    private string $password;

    /** @var string[] */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * @param callable(PasswordAuthenticatedUserInterface $user, string $password): string $passwordHasher
     */
    public function __construct(
        string $email,
        #[SensitiveParameter]
        string $password,
        array $roles,
        callable $passwordHasher,
    )
    {
        $this
            ->setEmail($email)
            ->setPassword($password, $passwordHasher)
            ->setRoles($roles);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $emailLength = strlen($email);
        Contract::requires(empty($email) === false, 'error.email.required');
        Contract::requires(filter_var($email, FILTER_VALIDATE_EMAIL) !== false, 'error.email.invalid');
        Contract::requires($emailLength >= 6 && $emailLength <= 60, 'error.email.length');

        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param callable(PasswordAuthenticatedUserInterface $user, string $password): string $passwordHasher
     */
    public function setPassword(#[SensitiveParameter] string $password, callable $passwordHasher): self
    {
        $passwordLength = strlen($password);
        Contract::requires(empty($password) === false, 'error.password.required');
        Contract::requires($passwordLength >= 8 && $passwordLength <= 255, 'error.password.length');

        $this->password = $passwordHasher($this, $password);
        return $this;
    }

    /**
     * @param callable(PasswordAuthenticatedUserInterface $user, string $password): bool $passwordValidator
     */
    public function validatePassword(#[SensitiveParameter] string $password, callable $passwordValidator): bool
    {
        return $passwordValidator($this, $password);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): self
    {
        Contract::requires(in_array($role, self::ROLES, true), 'error.role.invalid');

        if (in_array($role, $this->getRoles(), true)) {
            return $this;
        }

        $this->roles[] = $role;
        return $this;
    }

    public function removeRole(string $role): self
    {
        if (in_array($role, $this->getRoles(), true) === false || $role === self::ROLE_USER) {
            return $this;
        }

        $roleIndex = array_search($role, $this->getRoles(), true);

        if ($roleIndex === false) {
            return $this;
        }

        unset($this->roles[$roleIndex]);
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): self
    {
        return $this;
    }

    private function setRoles(array $roles): void
    {
        if (empty($roles)) {
            $this->roles = [self::ROLE_USER];
            return;
        }

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }
}
