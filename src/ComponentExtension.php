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

namespace Sulu\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * This Twig Extension manages the JavaScript components.
 */
class ComponentExtension extends AbstractExtension
{
    /**
     * @var array<int, array{name: string, id: string, options: mixed}>
     */
    protected $components = [];

    /**
     * @var array<string, int>
     */
    protected $instanceCounter = [];

    /**
     * @var array<int, array{name: string, func: string, args: mixed}>
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
            new TwigFunction('register_component', [$this, 'registerComponent'], ['deprecated' => true]),
            new TwigFunction('prepare_component', [$this, 'prepareComponent']),
            new TwigFunction('get_components', [$this, 'getComponents'], ['is_safe' => ['html']]),
            new TwigFunction('get_component_list', [$this, 'getComponentList'], ['is_safe' => ['html']]),
            new TwigFunction('call_service', [$this, 'callService'], ['deprecated' => true]),
            new TwigFunction('prepare_service', [$this, 'prepareService']),
            new TwigFunction('get_services', [$this, 'getServices'], ['is_safe' => ['html']]),
            new TwigFunction('get_service_list', [$this, 'getServiceList'], ['is_safe' => ['html']]),
            new TwigFunction('set_component_prefix', [$this, 'setComponentPrefix']),
        ];
    }

    /**
     * Prepare a new component and get a unique id.
     *
     * @param string $name
     * @param mixed[]|null $options
     * @param string $prefix
     *
     * @return string
     */
    public function registerComponent(string $name, ?array $options = null, ?string $prefix = ''): string
    {
        @trigger_error(
            __METHOD__ . ' is deprecated and will be removed in sulu/web-twig 3.0 use "prepareComponent" instead.',
            \E_USER_DEPRECATED
        );

        return $this->prepareComponent($name, $options, $prefix);
    }

    /**
     * Prepare a new component and get a unique id.
     *
     * @param string $name
     * @param mixed[]|null $options
     * @param string $prefix
     *
     * @return string
     */
    public function prepareComponent(string $name, ?array $options = null, ?string $prefix = ''): string
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
     * @return array<int, array{name: string, id: string, options: mixed}>|string|false
     */
    public function getComponents(bool $jsonEncode = true, bool $clear = true)
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
     * @return string|string[]|false
     */
    public function getComponentList(bool $jsonEncode = false)
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
     * Prepare a service call.
     *
     * @param string $name
     * @param string $function
     * @param mixed[] $parameters
     */
    public function callService(string $name, string $function, array $parameters = []): void
    {
        @trigger_error(
            __METHOD__ . ' is deprecated and will be removed in sulu/web-twig 3.0 use "prepareService" instead.',
            \E_USER_DEPRECATED
        );

        $this->prepareService($name, $function, $parameters);
    }

    /**
     * Prepare a service call.
     *
     * @param string $name
     * @param string $function
     * @param mixed[] $parameters
     */
    public function prepareService(string $name, string $function, array $parameters = []): void
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
     * @return array<int, array{name: string, func: string, args: mixed}>|string|false
     */
    public function getServices(bool $jsonEncode = true, bool $clear = true)
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
     * @return string|string[]|false
     */
    public function getServiceList(bool $jsonEncode = false)
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
    public function setComponentPrefix(string $componentPrefix): void
    {
        $this->componentPrefix = $componentPrefix;
    }
}
