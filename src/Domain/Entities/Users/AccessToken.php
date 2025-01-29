<?php

namespace App\Domain\Entities\Users;

use App\Domain\Entities\EntityInterface;
use App\Domain\Interfaces\RandomInterface;
use App\Domain\Validation\ValidationTrait;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\Table(name: 'access_tokens')]
class AccessToken implements EntityInterface
{
    use ValidationTrait;

    // TODO: remove id
    #[ORM\Id, ORM\Column(type: Types::INTEGER), ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class), ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $token;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $expiresAt;

    public function __construct(User $user, int $ttl, RandomInterface $random)
    {
        self::requires($user->getId() !== null, 'error.userId.required');

        $this->user = $user;
        $this->token = $random->generate();
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
