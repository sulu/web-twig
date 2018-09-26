<?php

use Massive\Component\Web\CountTwigExtension;
use PHPUnit\Framework\TestCase;

class CountTwigExtensionTest extends TestCase
{
    /**
     * @varCountTwigExtension
     */
    private $countTwigExtension;

    public function setup()
    {
        $this->countTwigExtension = new CountTwigExtension();
    }

    public function testCount()
    {
        $this->assertSame(1, $this->countTwigExtension->increaseCounter('test'));
        $this->assertSame(2, $this->countTwigExtension->increaseCounter('test'));
        $this->assertSame(1, $this->countTwigExtension->increaseCounter('example'));
        $this->assertSame(2, $this->countTwigExtension->increaseCounter('example'));
        $this->assertSame(2, $this->countTwigExtension->getCounter('example'));
        $this->countTwigExtension->resetCounter('test');
        $this->assertSame(3, $this->countTwigExtension->increaseCounter('example'));
        $this->assertSame(1, $this->countTwigExtension->increaseCounter('test'));
        $this->assertSame(1, $this->countTwigExtension->getCounter('test'));
        $this->countTwigExtension->resetCounter('example');
        $this->assertSame(1, $this->countTwigExtension->increaseCounter('example'));
    }
}
