<?php

namespace App\Domain\Users;

use App\Domain\EntityInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use SensitiveParameter;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity, ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EntityInterface
{
    public const string ROLE_AUTHOR = 'ROLE_AUTHOR';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';
    public const array ROLES = [self::ROLE_AUTHOR, self::ROLE_ADMIN];

    #[ORM\Id, ORM\Column(type: Types::INTEGER), ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $email;

    /** @var string[] */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(type: Types::STRING)]
    private string $password;

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
        $this->setEmail($email)
            ->setPassword($password, $passwordHasher)
            ->setRoles($roles);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = static::ROLE_AUTHOR;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
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
        $this->password = $passwordHasher($this, $password);
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
}
