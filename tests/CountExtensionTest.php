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
use Sulu\Twig\Extensions\CountExtension;

class CountExtensionTest extends TestCase
{
    /**
     * @var CountExtension
     */
    private $countExtension;

    public function setUp(): void
    {
        $this->countExtension = new CountExtension();
    }

    public function testCount(): void
    {
        $this->assertSame(1, $this->countExtension->increaseCounter('test'));
        $this->assertSame(2, $this->countExtension->increaseCounter('test'));
        $this->assertSame(1, $this->countExtension->increaseCounter('example'));
        $this->assertSame(2, $this->countExtension->increaseCounter('example'));
        $this->assertSame(2, $this->countExtension->getCounter('example'));
        $this->countExtension->resetCounter('test');
        $this->assertSame(3, $this->countExtension->increaseCounter('example'));
        $this->assertSame(1, $this->countExtension->increaseCounter('test'));
        $this->assertSame(1, $this->countExtension->getCounter('test'));
        $this->countExtension->resetCounter('example');
        $this->assertSame(1, $this->countExtension->increaseCounter('example'));
    }
}
