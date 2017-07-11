<?php

namespace Massive\Component\Web;

/**
 * This Twig Extension manages the JavaScript components.
 */
class ComponentTwigExtension extends \Twig_Extension
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
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('register_component', [$this, 'registerComponent']),
            new \Twig_SimpleFunction('get_components', [$this, 'getComponents'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('get_component_list', [$this, 'getComponentList'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('call_service', [$this, 'callService']),
            new \Twig_SimpleFunction('get_services', [$this, 'getServices'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('get_service_list', [$this, 'getServiceList'], ['is_safe' => ['html']]),
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
    public function registerComponent($name, $options = null, $prefix = '')
    {
        if (!isset($this->instanceCounter[$name])) {
            $this->instanceCounter[$name] = 0;
        }

        ++$this->instanceCounter[$name];

        $id = $prefix . $name . '-' . $this->instanceCounter[$name];

        if (is_array($options) && array_key_exists('id', $options)) {
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
     * @return string
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
     * @return string.
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
    public function callService($name, $function, $parameters = [])
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
     * @return array
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
     * @return string.
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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'massive_web';
    }
}
