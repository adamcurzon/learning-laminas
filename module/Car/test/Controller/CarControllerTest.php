<?php

declare(strict_types=1);

namespace CarTest\Controller;

use Laminas\Stdlib\ArrayUtils;
use Car\Controller\CarController;
use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CarControllerTest extends AbstractHttpControllerTestCase
{
    const APPLICATION_NAME = "car";

    public function setUp(): void
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testIndexActionCanBeAccessed(): void
    {
        $this->dispatch('/', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName(self::APPLICATION_NAME);
        $this->assertControllerName(CarController::class);
        $this->assertControllerClass('CarController');
        $this->assertMatchedRouteName('home');
    }

    public function testIndexActionViewModelTemplateRenderedWithinLayout(): void
    {
        $this->dispatch('/', 'GET');
        $this->assertQuery('body h1');
        $this->assertResponseStatusCode(200);
    }

    public function testInvalidRouteDoesNotCrash(): void
    {
        $this->dispatch('/car/edit/abc', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
