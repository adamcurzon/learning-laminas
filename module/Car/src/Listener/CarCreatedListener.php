<?php

namespace Car\Listener;

use Application\Service\LoggerService;
use Car\Controller\CarController;
use Car\Event\CarCreatedEvent;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\Event;

class CarCreatedListener extends AbstractListenerAggregate
{
    public function __construct(private LoggerService $loggerService)
    {
    }

    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $sharedEvents = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(CarController::class, CarCreatedEvent::NAME, [$this, 'onCarCreated']);
    }

    public function onCarCreated(Event $event): void
    {
        $car = $event->getTarget();

        $this->loggerService->info("Car created", [$car]);
    }
}
