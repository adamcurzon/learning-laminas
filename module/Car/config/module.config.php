<?php

namespace Car;

use Car\Model\CarTableFactory;
use Laminas\Router\Http\Segment;
use Car\Listener\CarCreatedListener;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\CarController::class => ReflectionBasedAbstractFactory::class
        ],
    ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'car' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/car[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CarController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'car' => __DIR__ . '/../view',
        ],
    ],

    'service_manager' => [
        'factories' => [
            Model\CarTable::class => CarTableFactory::class,
            CarCreatedListener::class => InvokableFactory::class,
        ],
    ],

    'listeners' => [
        CarCreatedListener::class,
    ],
];
