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

namespace Sulu\Component\Web\Twig\Tests;

use PHPUnit\Framework\TestCase;
use Sulu\Component\Web\Twig\CountTwigExtension;

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
