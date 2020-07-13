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

    public function testIconFontDefaultAttributes(): void
    {
        $iconExtension = new IconExtension('font', ['role' => 'none']);

        $this->assertSame(
            '<span role="none" class="icon icon-test"></span>',
            $iconExtension->getIcon('test')
        );
    }

    public function testIconFontRemoveDefaultAttributes(): void
    {
        $iconExtension = new IconExtension('font', ['role' => 'none']);

        $this->assertSame(
            '<span class="icon icon-test"></span>',
            $iconExtension->getIcon('test', ['role' => null])
        );
    }

    public function testIconFontAttributes(): void
    {
        $iconExtension = new IconExtension('font');

        $this->assertSame(
            '<span role="none" class="icon icon-test"></span>',
            $iconExtension->getIcon('test', ['role' => 'none'])
        );
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

    public function testIconFontOtherGroup(): void
    {
        $iconExtension = new IconExtension([
            'other' => [
                'type' => 'font',
            ],
        ]);

        $this->assertSame(
            '<span class="icon icon-test"></span>',
            $iconExtension->getIcon('test', null, 'other')
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

    public function testSvgIconAttributes(): void
    {
        $iconExtension = new IconExtension([
            'default' => [
                'type' => 'svg',
                'path' => '/path/to/symbol-defs.svg',
            ],
        ]);

        $this->assertSame(
            '<svg role="none" class="icon icon-test"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>',
            $iconExtension->getIcon('test', ['role' => 'none'])
        );
    }

    public function testSvgIconDefaultAttributes(): void
    {
        $iconExtension = new IconExtension(
            [
                'default' => [
                    'type' => 'svg',
                    'path' => '/path/to/symbol-defs.svg',
                ],
            ],
            [
                'role' => 'none',
            ]
        );

        $this->assertSame(
            '<svg role="none" class="icon icon-test"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>',
            $iconExtension->getIcon('test')
        );
    }

    public function testSvgIconRemoveDefaultAttributes(): void
    {
        $iconExtension = new IconExtension(
            [
                'default' => [
                    'type' => 'svg',
                    'path' => '/path/to/symbol-defs.svg',
                ],
            ],
            [
                'role' => 'none',
            ]
        );

        $this->assertSame(
            '<svg class="icon icon-test"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>',
            $iconExtension->getIcon('test', ['role' => null])
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

    public function testSvgIconOtherGroup(): void
    {
        $iconExtension = new IconExtension([
            'other' => [
                'type' => 'svg',
                'path' => '/path/to/symbol-defs.svg',
            ],
        ]);

        $this->assertSame(
            '<svg class="icon icon-test"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>',
            $iconExtension->getIcon('test', null, 'other')
        );
    }
}
