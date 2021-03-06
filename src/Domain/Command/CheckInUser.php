<?php

declare(strict_types=1);

namespace Building\Domain\Command;

use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

final class CheckInUser extends Command
{
    /**
     * @var Uuid
     */
    private $buildingId;
    /**
     * @var string
     */
    private $username;

    /**
     * @var \DateTime
     */
    private $checkInAt;

    private function __construct(Uuid $buildingId, string $username)
    {
        $this->init();

        $this->buildingId = $buildingId;
        $this->username = $username;
        $this->checkInAt = new \DateTime();
    }

    public static function fromBuildingIdAndUsername(Uuid $buildingId, string $username) : self
    {
        return new self($buildingId, $username);
    }

    public function username() : string
    {
        return $this->username;
    }

    public function buildingId() : Uuid
    {
        return $this->buildingId;
    }

    /**
     * {@inheritDoc}
     */
    public function payload() : array
    {
        return [
            'username' => $this->username,
            'checkInAt' => $this->checkInAt,
            'buildingId' => $this->buildingId->toString(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function setPayload(array $payload)
    {
        $this->username = $payload['username'];
        $this->checkInAt = $payload['checkInAt'];
        $this->buildingId = Uuid::fromString($payload['buildingId']);
    }
}
