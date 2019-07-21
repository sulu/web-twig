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

namespace Sulu\Twig\Extensions\Tests;

use PHPUnit\Framework\TestCase;
use Sulu\Twig\Extensions\ComponentTwigExtension;

class ComponentTwigExtensionTest extends TestCase
{
    /**
     * @var ComponentTwigExtension
     */
    private $componentTwigExtension;

    public function setup()
    {
        $this->componentTwigExtension = new ComponentTwigExtension();
    }

    public function testRegisterComponent(): void
    {
        $this->assertSame('test-1', $this->componentTwigExtension->registerComponent('test'));

        $components = $this->componentTwigExtension->getComponents();
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

    public function testRegisterMultipleComponent(): void
    {
        $this->assertSame('test-1', $this->componentTwigExtension->registerComponent('test'));
        $this->assertSame('test-2', $this->componentTwigExtension->registerComponent('test'));

        $components = $this->componentTwigExtension->getComponents();
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

    public function testRegisterPrefix(): void
    {
        $this->componentTwigExtension->setComponentPrefix('partial-');
        $this->assertSame('partial-test-1', $this->componentTwigExtension->registerComponent('test'));
        $this->assertSame('partial-test-2', $this->componentTwigExtension->registerComponent('test'));

        $components = $this->componentTwigExtension->getComponents();
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

    public function testRegisterCustomIdComponent(): void
    {
        $this->assertSame('custom', $this->componentTwigExtension->registerComponent('test', ['id' => 'custom']));
        $components = $this->componentTwigExtension->getComponents();
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

    public function testRegisterOptionComponent(): void
    {
        $this->assertSame('test-1', $this->componentTwigExtension->registerComponent('test', ['option1' => 'value1', 'option2' => 'value2']));

        $components = $this->componentTwigExtension->getComponents();
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

    public function testRegisterComponentArray(): void
    {
        $this->assertSame('test-1', $this->componentTwigExtension->registerComponent('test'));

        $components = $this->componentTwigExtension->getComponents(false);
        $this->assertNotFalse($components);
        $this->assertIsObject($components[0]['options']);
        unset($components[0]['options']);

        $this->assertSame(
            [
                [
                    'name' => 'test',
                    'id' => 'test-1',
                ],
            ],
            $components
        );
    }

    public function testRegisterComponentClear(): void
    {
        $this->assertSame('test-1', $this->componentTwigExtension->registerComponent('test'));

        // Get components without clearing them.
        $components = $this->componentTwigExtension->getComponents(true, false);
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
        $components = $this->componentTwigExtension->getComponents();
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
        $this->assertSame([], $this->componentTwigExtension->getComponents(false));
    }

    public function testComponentList(): void
    {
        $this->componentTwigExtension->registerComponent('test');
        $this->componentTwigExtension->registerComponent('test');
        $this->componentTwigExtension->registerComponent('test2');
        $this->componentTwigExtension->registerComponent('test3');

        $componentList = $this->componentTwigExtension->getComponentList();

        $this->assertIsArray($componentList);
        $this->assertCount(3, $componentList);
        $this->assertSame(['test', 'test2', 'test3'], $componentList);
    }

    public function testCallService(): void
    {
        $this->componentTwigExtension->callService('service', 'function', ['key' => 'value']);

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
            $this->componentTwigExtension->getServices()
        );
    }

    public function testGetServices(): void
    {
        $this->componentTwigExtension->callService('service', 'function', ['key' => 'value']);
        $this->componentTwigExtension->callService('service2', 'function', ['key' => 'value']);
        $this->componentTwigExtension->callService('service2', 'function', ['key' => 'value']);
        $this->componentTwigExtension->callService('service3', 'function', ['key' => 'value']);

        $servicesList = $this->componentTwigExtension->getServiceList();

        $this->assertIsArray($servicesList);
        $this->assertCount(3, $servicesList);
        $this->assertSame(['service', 'service2', 'service3'], $servicesList);
    }
}
