<?php

declare(strict_types=1);

namespace Building\Domain\DomainEvent;

use Prooph\EventSourcing\AggregateChanged;
use Rhumsaa\Uuid\Uuid;

final class CheckInOutAnomalyDetected extends AggregateChanged
{
    const TYPE_ERROR_CHECKIN = 'checkin';
    const TYPE_ERROR_CHECKOUT = 'checkout';

    public static function generateCheckInError(
        Uuid $buildingId,
        string $username
    ) : self {
        return self::occur((string) $buildingId, ['username' => $username, 'type' => self::TYPE_ERROR_CHECKIN]);
    }

    public static function generateCheckOutError(
        Uuid $buildingId,
        string $username
        ) : self {
        return self::occur((string) $buildingId, ['username' => $username, 'type' => self::TYPE_ERROR_CHECKOUT]);
    }

    public function username() : string
    {
        return $this->payload['username'];
    }

    public function type() : string
    {
        return $this->payload['type'];
    }

    public function buildingId() : Uuid
    {
        return Uuid::fromString($this->aggregateId());
    }
}
