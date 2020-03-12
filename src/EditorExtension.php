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
use Twig\TwigFilter;

/**
 * This Twig Extension to wrap text editor output with specific div.
 */
class EditorExtension extends AbstractExtension
{
    /**
     * @var string[]
     */
    protected $addClasses;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @param string[] $addClasses
     */
    public function __construct(array $addClasses = [], string $className = 'editor', string $tag = 'div')
    {
        $this->addClasses = $addClasses;
        $this->className = $className;
        $this->tag = $tag;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('editor', [$this, 'editor'], ['is_safe' => ['html']]),
            new TwigFilter('editor_classes', [$this, 'editorClasses'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Returns a given html with a wrapped tag and class.
     *
     * @param string|null $html
     * @param string|null $tag
     * @param string|null $className
     *
     * @return string
     */
    public function editor(?string $html, ?string $tag = null, ?string $className = null): string
    {
        if (null === $html) {
            return '';
        }

        $className = $className ?: $this->className;
        $tag = $tag ?: $this->tag;

        return '<' . $tag . ' class="' . $className . '">' . $html . '</' . $tag . '>';
    }

    /**
     * Add specified classes to tags.
     *
     * @param string|null $html
     * @param string[] $addClasses
     *
     * @return string
     */
    public function editorClasses(?string $html, array $addClasses = []): string
    {
        if (null === $html) {
            return '';
        }

        $addClasses = array_merge($this->addClasses, $addClasses);

        $tags = [];
        $tagsWithClasses = [];
        foreach ($addClasses as $tag => $class) {
            $tags[] = '<' . $tag . '>';
            $tagsWithClasses[] = '<' . $tag . ' class="' . $class . '">';
        }

        return str_replace($tags, $tagsWithClasses, $html);
    }
}
