<?php

namespace Massive\Component\Web;

class CountTwigExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    private $counters = [];

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('counter', [$this, 'increaseCounter']),
            new \Twig_SimpleFunction('reset_counter', [$this, 'resetCounter']),
            new \Twig_SimpleFunction('get_counter', [$this, 'getCounter']),
        ];
    }

    public function increaseCounter($group)
    {
        if (!isset($this->counters[$group])) {
            $this->counters[$group] = 0;
        }

        return ++$this->counters[$group];
    }

    public function resetCounter($group)
    {
        $this->counters[$group] = 0;
    }

    public function getCounter($group)
    {
        return isset($this->counters[$group]) ? $this->counters[$group] : 0;
    }
}
