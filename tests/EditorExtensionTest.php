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
use Sulu\Twig\Extensions\EditorExtension;

class EditorExtensionTest extends TestCase
{
    /**
     * @var EditorExtension
     */
    private $editorExtension;

    public function setup()
    {
        $this->editorExtension = new EditorExtension();
    }

    public function testEditor(): void
    {
        $this->assertSame(
            '<div class="editor"><p>Test</p></div>',
            $this->editorExtension->editor('<p>Test</p>')
        );
    }

    public function testEditorWithNull(): void
    {
        $this->assertSame(
            '<div class="editor"></div>',
            $this->editorExtension->editor(null)
        );
    }

    public function testEditorCustomClass(): void
    {
        $this->assertSame(
            '<div class="custom"><p>Test</p></div>',
            $this->editorExtension->editor('<p>Test</p>', null, 'custom')
        );
    }

    public function testEditorCustomTag(): void
    {
        $this->assertSame(
            '<section class="editor"><p>Test</p></section>',
            $this->editorExtension->editor('<p>Test</p>', 'section')
        );
    }

    public function testEditorAddClasses(): void
    {
        $this->assertSame(
            '<ul class="list"><li>List</li></ul>',
            $this->editorExtension->editorClasses('<ul><li>List</li></ul>', ['ul' => 'list'])
        );
    }

    public function testEditorAddClassesWithNull(): void
    {
        $this->assertSame(
            '',
            $this->editorExtension->editorClasses(null)
        );
    }

    public function testEditorAddClassesWithFalsy(): void
    {
        $this->assertSame(
            '0',
            $this->editorExtension->editorClasses('0')
        );
    }
}
