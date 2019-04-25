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

namespace Sulu\Component\Web\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * This Twig Extension manages the JavaScript components.
 */
class ComponentTwigExtension extends AbstractExtension
{
    /**
     * @var array
     */
    protected $components = [];

    /**
     * @var array
     */
    protected $instanceCounter = [];

    /**
     * @var array
     */
    protected $services = [];

    /**
     * @var string
     */
    protected $componentPrefix = '';

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('register_component', [$this, 'registerComponent']),
            new TwigFunction('get_components', [$this, 'getComponents'], ['is_safe' => ['html']]),
            new TwigFunction('get_component_list', [$this, 'getComponentList'], ['is_safe' => ['html']]),
            new TwigFunction('call_service', [$this, 'callService']),
            new TwigFunction('get_services', [$this, 'getServices'], ['is_safe' => ['html']]),
            new TwigFunction('get_service_list', [$this, 'getServiceList'], ['is_safe' => ['html']]),
            new TwigFunction('set_component_prefix', [$this, 'setComponentPrefix']),
        ];
    }

    /**
     * Register a new component and get a unique id.
     *
     * @param string $name
     * @param array $options
     * @param string $prefix
     *
     * @return string
     */
    public function registerComponent($name, $options = null, $prefix = ''): string
    {
        if (!isset($this->instanceCounter[$name])) {
            $this->instanceCounter[$name] = 0;
        }

        ++$this->instanceCounter[$name];

        $id = $this->componentPrefix . $prefix . $name . '-' . $this->instanceCounter[$name];

        if (\is_array($options) && \array_key_exists('id', $options)) {
            $id = $options['id'];
        }

        if (empty($options)) {
            // Create stdClass if $options is empty, otherwise json_encode would return an array instead of an object.
            $options = new \stdClass();
        }

        $component = [
            'name' => $name,
            'id' => $id,
            'options' => $options,
        ];

        $this->components[] = $component;

        return $id;
    }

    /**
     * Get all registered components.
     *
     * @param bool $jsonEncode
     * @param bool $clear
     *
     * @return string|array|false
     */
    public function getComponents($jsonEncode = true, $clear = true)
    {
        $components = $this->components;

        if ($clear) {
            $this->components = [];
        }

        return $jsonEncode ? json_encode($components) : $components;
    }

    /**
     * Get component list.
     *
     * @param bool $jsonEncode
     *
     * @return string|array|false
     */
    public function getComponentList($jsonEncode = false)
    {
        $components = [];

        foreach ($this->components as $component) {
            $name = $component['name'];
            $components[$name] = $name;
        }

        $components = array_values($components);

        return $jsonEncode ? json_encode($components) : $components;
    }

    /**
     * Call a service function.
     *
     * @param string $name
     * @param string $function
     * @param array $parameters
     */
    public function callService($name, $function, $parameters = []): void
    {
        $this->services[] = [
            'name' => $name,
            'func' => $function,
            'args' => $parameters,
        ];
    }

    /**
     * Return all register service functions.
     *
     * @param bool $jsonEncode
     * @param bool $clear
     *
     * @return array|string|false
     */
    public function getServices($jsonEncode = true, $clear = true)
    {
        $services = $this->services;

        if ($clear) {
            $this->services = [];
        }

        return $jsonEncode ? json_encode($services) : $services;
    }

    /**
     * Get service list.
     *
     * @param bool $jsonEncode
     *
     * @return string|array|false
     */
    public function getServiceList($jsonEncode = false)
    {
        $services = [];

        foreach ($this->services as $service) {
            $name = $service['name'];
            $services[$name] = $name;
        }

        $services = array_values($services);

        return $jsonEncode ? json_encode($services) : $services;
    }

    /**
     * Set component prefix.
     *
     * @param string $componentPrefix
     */
    public function setComponentPrefix($componentPrefix): void
    {
        $this->componentPrefix = $componentPrefix;
    }
}
