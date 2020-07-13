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
use Sulu\Twig\Extensions\IconExtension;

class IconExtensionTest extends TestCase
{
    public function testIconFont(): void
    {
        $iconExtension = new IconExtension('font');

        $this->assertSame(
            '<span class="icon icon-test"></span>',
            $iconExtension->getIcon('test')
        );
    }

    public function testFalseIconSet(): void
    {
        $iconExtension = new IconExtension([
            'default' => 'font',
            'special' => 'font',
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Icon Set with name "other" not found, found: "default", "special".');

        $iconExtension->getIcon('test', null, 'other');
    }

    public function testIconFontCustomSettings(): void
    {
        $iconExtension = new IconExtension([
            'other' => [
                'type' => 'font',
                'className' => 'my-icon',
                'classPrefix' => 'my-icon-',
                'classSuffix' => '-new',
            ],
        ]);

        $this->assertSame(
            '<span class="add-class my-icon my-icon-test-new"></span>',
            $iconExtension->getIcon('test', 'add-class', 'other')
        );
    }

    public function testSvgIcon(): void
    {
        $iconExtension = new IconExtension([
            'default' => [
                'type' => 'svg',
                'path' => '/path/to/symbol-defs.svg',
            ],
        ]);

        $this->assertSame(
            '<svg class="icon icon-test"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>',
            $iconExtension->getIcon('test')
        );
    }

    public function testSvgIconCustomSettings(): void
    {
        $iconExtension = new IconExtension([
            'other' => [
                'type' => 'svg',
                'path' => '/path/to/symbol-defs.svg',
                'className' => 'my-icon',
                'classPrefix' => 'my-icon-',
                'classSuffix' => '-new',
            ],
        ]);

        $this->assertSame(
            '<svg class="add-class my-icon my-icon-test-new"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>',
            $iconExtension->getIcon('test', 'add-class', 'other')
        );
    }
}
