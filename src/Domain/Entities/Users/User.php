<?php

declare(strict_types=1);

namespace App\Domain\Entities\Users;

use App\Domain\Entities\EntityInterface;
use App\Domain\Validation\ValidationTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SensitiveParameter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity, ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
    use ValidationTrait;

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
    #[ORM\Column(type: Types::JSON, options: ['default' => '["ROLE_USER"]'])]
    private array $roles = [self::ROLE_USER];

    public function __construct(
        string $email,
        #[SensitiveParameter]
        string $password,
        array $roles,
        UserPasswordHasherInterface $passwordHasher,
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
        self::requires(empty($email) === false, 'error.email.required');
        self::requires(filter_var($email, FILTER_VALIDATE_EMAIL) !== false, 'error.email.invalid');
        self::requires($emailLength >= 6 && $emailLength <= 60, 'error.email.length');

        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(
        #[SensitiveParameter] string $password,
        UserPasswordHasherInterface $passwordHasher,
    ): self
    {
        $passwordLength = strlen($password);
        self::requires(empty($password) === false, 'error.password.required');
        self::requires($passwordLength >= 8 && $passwordLength <= 255, 'error.password.length');

        $this->password = $passwordHasher->hashPassword($this, $password);
        return $this;
    }

    public function validatePassword(
        #[SensitiveParameter] string $password,
        UserPasswordHasherInterface $passwordHasher,
    ): bool
    {
        return $passwordHasher->isPasswordValid($this, $password);
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function addRole(string $role): self
    {
        self::requires(in_array($role, self::ROLES, true), 'error.role.invalid');

        if (in_array($role, $this->getRoles(), true)) {
            return $this;
        }

        $this->roles[] = $role;
        return $this;
    }

    public function removeRole(string $role): self
    {
        // Restrict removing the default role
        if ($role === self::ROLE_USER) {
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

        $roles[] = self::ROLE_USER;
        $roles = array_unique($roles);

        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }
}
