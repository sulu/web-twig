<?php

namespace FOA\Component\Twig;

/**
 * This Twig Extension manages the JavaScript components.
 */
class CoreTwigExtension extends \Twig_Extension
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
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('register_component', [$this, 'registerComponent']),
            new \Twig_SimpleFunction('get_components', [$this, 'getComponents'], ['is_safe' => ['html']]),
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
            'instanceId' => $id,
            'options' => $options,
        ];

        $this->components[] = $component;

        return $id;
    }

    /**
     * Get all registered components.
     *
     * @return string
     */
    public function getComponents()
    {
        return json_encode($this->components);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fao_core';
    }
}
