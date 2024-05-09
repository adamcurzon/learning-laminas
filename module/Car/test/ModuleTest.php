<?php

declare(strict_types=1);

namespace CarTest;

use Application\Module;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Car\Module;
 */
class ModuleTest extends TestCase
{
    public function testProvidesConfig(): void
    {
        $module = new Module();
        $config = $module->getConfig();

        self::assertArrayHasKey('router', $config);
        self::assertArrayHasKey('controllers', $config);
    }
}
