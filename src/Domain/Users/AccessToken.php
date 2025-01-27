<?php

namespace App\Domain\Users;

use App\Domain\EntityInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Random\RandomException;

#[ORM\Entity, ORM\Table(name: 'access_tokens')]
class AccessToken implements EntityInterface
{
    #[ORM\Id, ORM\Column(type: Types::INTEGER), ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class), ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $token;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $expiresAt;

    /**
     * @throws InvalidArgumentException
     * @throws RandomException
     */
    public function __construct(User $user, int $ttl)
    {
        if ($user->getId() === null) {
            throw new InvalidArgumentException('User must not be null');
        }

        $this->user = $user;
        $this->token = bin2hex(random_bytes(32));
        $this->expiresAt = new DateTimeImmutable("+$ttl seconds");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return $this->getExpiresAt()->getTimestamp() <= time();
    }
}
