<?php

namespace Car\Listener;

use Car\Controller\CarController;
use Car\Event\CarCreatedEvent;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\Event;

class CarCreatedListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events, $priority = 1): void
    {
        $sharedEvents = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(CarController::class, CarCreatedEvent::NAME, [$this, 'onCarCreated']);
    }

    public function onCarCreated(Event $event): void
    {
        $car = $event->getTarget();

        $fileHandle = fopen("log_file.log", 'a');
        fwrite($fileHandle, "Created car " . $car->name . "\n");
        fclose($fileHandle);
    }
}
