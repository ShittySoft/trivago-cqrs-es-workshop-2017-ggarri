<?php

declare(strict_types=1);

namespace Building\Domain\Command;

use Prooph\Common\Messaging\Command;
use Rhumsaa\Uuid\Uuid;

final class NotifyAnomaly extends Command
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
     * @var string
     */
    private $type;

    private function __construct(Uuid $buildingId, string $username, string $type)
    {
        $this->init();
        $this->buildingId = $buildingId;
        $this->username = $username;
        $this->type = $type;
    }

    public static function fromBuildingIdAndUsername(Uuid $buildingId, string $username, string $type) : self
    {
        return new self($buildingId, $username, $type);
    }

    public function username() : string
    {
        return $this->username;
    }

    public function buildingId() : Uuid
    {
        return $this->buildingId;
    }

    public function type() : string
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function payload() : array
    {
        return [
            'username' => $this->username,
            'buildingId' => $this->buildingId->toString(),
            'type' => $this->type
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function setPayload(array $payload)
    {
        $this->username = $payload['username'];
        $this->checkOutAt = $payload['checkOutAt'];
        $this->buildingId = Uuid::fromString($payload['buildingId']);
    }
}
