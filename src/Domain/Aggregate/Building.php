<?php

declare(strict_types=1);

namespace Building\Domain\Aggregate;

use Building\Domain\DomainEvent\CheckInOutAnomalyDetected;
use Building\Domain\DomainEvent\NewBuildingWasRegistered;
use Building\Domain\DomainEvent\UserCheckedIn;
use Building\Domain\DomainEvent\UserCheckedOut;
use Prooph\EventSourcing\AggregateRoot;
use Rhumsaa\Uuid\Console\Exception;
use Rhumsaa\Uuid\Uuid;

final class Building extends AggregateRoot
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var string
     */
    private $name;

    private $usersInBuilding = [];

    public static function new(string $name) : self
    {
        $self = new self();

        $self->recordThat(NewBuildingWasRegistered::fromBuildingIdAndName(
            Uuid::uuid4(),
            $name
        ));

        return $self;
    }

    public function checkInUser(string $username)
    {
        if(array_key_exists($username, $this->usersInBuilding)) {
            $this->recordThat(CheckInOutAnomalyDetected::generateCheckInError(
                $this->uuid,
                $username
            ));
        } else {
            $this->recordThat(UserCheckedIn::fromBuildingIdAndUsername(
                $this->uuid,
                $username
            ));
        }
    }

    public function checkOutUser(string $username)
    {
        if(!array_key_exists($username, $this->usersInBuilding)) {
            $this->recordThat(CheckInOutAnomalyDetected::generateCheckOutError(
                $this->uuid,
                $username
            ));
        } else {
            $this->recordThat(UserCheckedOut::fromBuildingIdAndUsername(
                $this->uuid,
                $username
            ));
        }
    }

    public function whenNewBuildingWasRegistered(NewBuildingWasRegistered $event)
    {
        $this->uuid = $event->uuid();
        $this->name = $event->name();
    }

    public function whenUserCheckedIn(UserCheckedIn $event)
    {
        $this->usersInBuilding[$event->username()] = true;
    }

    public function whenUserCheckedOut(UserCheckedOut $event)
    {
        unset($this->usersInBuilding[$event->username()]);
    }

    public function whenCheckInOutAnomalyDetected(CheckInOutAnomalyDetected $event)
    {
        // Do nothing
    }

    /**
     * {@inheritDoc}
     */
    protected function aggregateId() : string
    {
        return (string) $this->uuid;
    }

    /**
     * {@inheritDoc}
     */
    public function id() : string
    {
        return $this->aggregateId();
    }
}
