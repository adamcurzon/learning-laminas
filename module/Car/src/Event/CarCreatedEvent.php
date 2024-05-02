<?php

namespace Car\Event;

use Laminas\EventManager\Event;

class CarCreatedEvent extends Event
{
    const NAME = 'car.created';

    public function __construct(protected $target)
    {
        parent::__construct(self::NAME, $target);
    }
}
