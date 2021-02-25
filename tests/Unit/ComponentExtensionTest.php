<?php

declare(strict_types=1);

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Twig\Extensions\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sulu\Twig\Extensions\ComponentExtension;

class ComponentExtensionTest extends TestCase
{
    /**
     * @var ComponentExtension
     */
    private $componentExtension;

    protected function setUp(): void
    {
        $this->componentExtension = new ComponentExtension();
    }

    public function testPrepareComponent(): void
    {
        $this->assertSame('test-1', $this->componentExtension->prepareComponent('test'));

        $components = $this->componentExtension->getComponents();
        $this->assertNotFalse($components);
        $this->assertSame(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => new \stdClass(),
                ],
            ]),
            $components
        );
    }

    public function testPrepareMultipleComponent(): void
    {
        $this->assertSame('test-1', $this->componentExtension->prepareComponent('test'));
        // test deprecated registerComponent function
        $this->assertSame('test-2', $this->componentExtension->registerComponent('test'));

        $components = $this->componentExtension->getComponents();
        $this->assertNotFalse($components);
        $this->assertSame(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => new \stdClass(),
                ],
                [
                    'name' => 'test',
                    'id' => 'test-2',
                    'options' => new \stdClass(),
                ],
            ]),
            $components
        );
    }

    public function testPreparePrefix(): void
    {
        $this->componentExtension->setComponentPrefix('partial-');
        $this->assertSame('partial-test-1', $this->componentExtension->prepareComponent('test'));
        $this->assertSame('partial-test-2', $this->componentExtension->prepareComponent('test'));

        $components = $this->componentExtension->getComponents();
        $this->assertNotFalse($components);
        $this->assertSame(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'partial-test-1',
                    'options' => new \stdClass(),
                ],
                [
                    'name' => 'test',
                    'id' => 'partial-test-2',
                    'options' => new \stdClass(),
                ],
            ]),
            $components
        );
    }

    public function testPrepareCustomIdComponent(): void
    {
        $this->assertSame('custom', $this->componentExtension->prepareComponent('test', ['id' => 'custom']));
        $components = $this->componentExtension->getComponents();
        $this->assertNotFalse($components);
        $this->assertSame(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'custom',
                    'options' => (object) ['id' => 'custom'],
                ],
            ]),
            $components
        );
    }

    public function testPrepareOptionComponent(): void
    {
        $this->assertSame('test-1', $this->componentExtension->prepareComponent('test', ['option1' => 'value1', 'option2' => 'value2']));

        $components = $this->componentExtension->getComponents();
        $this->assertNotFalse($components);
        $this->assertSame(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => (object) [
                        'option1' => 'value1',
                        'option2' => 'value2',
                    ],
                ],
            ]),
            $components
        );
    }

    public function testPrepareComponentArray(): void
    {
        $this->assertSame('test-1', $this->componentExtension->prepareComponent('test'));

        /** @var array<int, array{name: string, id: string, options: mixed}> $components */
        $components = $this->componentExtension->getComponents(false);
        $this->assertNotFalse($components);
        $this->assertIsObject($components[0]['options']);

        $this->assertSame(
            [
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => $components[0]['options'],
                ],
            ],
            $components
        );
    }

    public function testPrepareComponentClear(): void
    {
        $this->assertSame('test-1', $this->componentExtension->prepareComponent('test'));

        // Get components without clearing them.
        $components = $this->componentExtension->getComponents(true, false);
        $this->assertNotFalse($components);
        $this->assertSame(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => (object) [],
                ],
            ]),
            $components
        );

        // Get components with clearing.
        $components = $this->componentExtension->getComponents();
        $this->assertNotFalse($components);
        $this->assertSame(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => (object) [],
                ],
            ]),
            $components
        );

        // Check if cleared correctly.
        $this->assertSame([], $this->componentExtension->getComponents(false));
    }

    public function testComponentList(): void
    {
        $this->componentExtension->prepareComponent('test');
        $this->componentExtension->prepareComponent('test');
        $this->componentExtension->prepareComponent('test2');
        $this->componentExtension->prepareComponent('test3');

        $componentList = $this->componentExtension->getComponentList();

        $this->assertIsArray($componentList);
        $this->assertCount(3, $componentList);
        $this->assertSame(['test', 'test2', 'test3'], $componentList);
    }

    public function testPrepareService(): void
    {
        $this->componentExtension->prepareService('service', 'function', ['key' => 'value']);

        $this->assertSame(
            json_encode([
                [
                    'name' => 'service',
                    'func' => 'function',
                    'args' => [
                        'key' => 'value',
                    ],
                ],
            ]),
            $this->componentExtension->getServices()
        );
    }

    public function testPrepareServices(): void
    {
        $this->componentExtension->prepareService('service', 'function', ['key' => 'value']);
        $this->componentExtension->prepareService('service2', 'function', ['key' => 'value']);
        $this->componentExtension->prepareService('service2', 'function', ['key' => 'value']);
        // test deprecated service call
        $this->componentExtension->callService('service3', 'function', ['key' => 'value']);

        $servicesList = $this->componentExtension->getServiceList();

        $this->assertIsArray($servicesList);
        $this->assertCount(3, $servicesList);
        $this->assertSame(['service', 'service2', 'service3'], $servicesList);
    }
}
