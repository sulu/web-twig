<?php

use Massive\Component\Web\ComponentTwigExtension;
use PHPUnit\Framework\TestCase;

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

    public function testRegisterComponent()
    {
        $this->assertEquals('test-1', $this->componentTwigExtension->registerComponent('test'));

        $this->assertEquals(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => new stdClass(),
                ],
            ]),
            $this->componentTwigExtension->getComponents()
        );
    }

    public function testRegisterMultipleComponent()
    {
        $this->assertEquals('test-1', $this->componentTwigExtension->registerComponent('test'));
        $this->assertEquals('test-2', $this->componentTwigExtension->registerComponent('test'));

        $this->assertEquals(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => new stdClass(),
                ],
                [
                    'name' => 'test',
                    'id' => 'test-2',
                    'options' => new stdClass(),
                ],
            ]),
            $this->componentTwigExtension->getComponents()
        );
    }

    public function testRegisterCustomIdComponent()
    {
        $this->assertEquals('custom', $this->componentTwigExtension->registerComponent('test', ['id' => 'custom']));

        $this->assertEquals(
            json_encode([
                [
                    'name' => 'test',
                    'id' => 'custom',
                    'options' => (object) ['id' => 'custom'],
                ],
            ]),
            $this->componentTwigExtension->getComponents()
        );
    }

    public function testRegisterOptionComponent()
    {
        $this->assertEquals('test-1', $this->componentTwigExtension->registerComponent('test', ['option1' => 'value1', 'option2' => 'value2']));

        $this->assertEquals(
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
            $this->componentTwigExtension->getComponents()
        );
    }

    public function testRegisterComponentArray()
    {
        $this->assertEquals('test-1', $this->componentTwigExtension->registerComponent('test'));

        $this->assertEquals(
            [
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => new stdClass(),
                ],
            ],
            $this->componentTwigExtension->getComponents(false)
        );
    }

    public function testRegisterComponentClear()
    {
        $this->assertEquals('test-1', $this->componentTwigExtension->registerComponent('test'));

        // Get components without clearing them.
        $this->assertEquals(
            [
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => new stdClass(),
                ],
            ],
            $this->componentTwigExtension->getComponents(false, false)
        );

        // Get components with clearing.
        $this->assertEquals(
            [
                [
                    'name' => 'test',
                    'id' => 'test-1',
                    'options' => new stdClass(),
                ],
            ],
            $this->componentTwigExtension->getComponents(false)
        );

        // Check if cleared correctly.
        $this->assertEquals([], $this->componentTwigExtension->getComponents(false));
    }

    public function testComponentList()
    {
        $this->componentTwigExtension->registerComponent('test');
        $this->componentTwigExtension->registerComponent('test');
        $this->componentTwigExtension->registerComponent('test2');
        $this->componentTwigExtension->registerComponent('test3');

        $componentList = $this->componentTwigExtension->getComponentList();

        $this->assertCount(3, $componentList);
        $this->assertEquals(['test', 'test2', 'test3'], $componentList);
    }

    public function testCallService()
    {
        $this->componentTwigExtension->callService('service', 'function', ['key' => 'value']);

        $this->assertEquals(
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

    public function testGetServices()
    {
        $this->componentTwigExtension->callService('service', 'function', ['key' => 'value']);
        $this->componentTwigExtension->callService('service2', 'function', ['key' => 'value']);
        $this->componentTwigExtension->callService('service2', 'function', ['key' => 'value']);
        $this->componentTwigExtension->callService('service3', 'function', ['key' => 'value']);

        $servicesList = $this->componentTwigExtension->getServiceList();

        $this->assertCount(3, $servicesList);
        $this->assertEquals(['service', 'service2', 'service3'], $servicesList);
    }
}
