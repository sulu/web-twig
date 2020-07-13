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
use Twig\TwigFunction;

/**
 * This Twig Extension manage icomoon icons and allow easy switch between svg and font icons.
 */
class IconExtension extends AbstractExtension
{
    const ICON_SET_TYPE_SVG = 'svg';
    const ICON_SET_TYPE_FONT = 'font';

    /**
     * @var mixed[]
     */
    private $iconSets = [];

    /**
     * @param string|mixed[] $iconSets
     */
    public function __construct($iconSets)
    {
        if (\is_string($iconSets)) {
            $iconSets = [
                'default' => $iconSets,
            ];
        }

        foreach ($iconSets as $iconSetName => $iconSet) {
            if (\is_string($iconSet)) {
                if (self::ICON_SET_TYPE_FONT === $iconSet) {
                    $iconSet = [
                        'type' => self::ICON_SET_TYPE_FONT,
                    ];
                } else {
                    $iconSet = [
                        'type' => self::ICON_SET_TYPE_SVG,
                        'path' => $iconSet,
                    ];
                }
            }

            if (self::ICON_SET_TYPE_SVG === $iconSet['type'] && !isset($iconSet['path'])) {
                throw new \LogicException('Expected "path" to be set for "svg" icon.');
            }

            $iconSet['className'] = $iconSet['className'] ?? 'icon';
            $iconSet['classPrefix'] = $iconSet['classPrefix'] ?? 'icon-';
            $iconSet['classSuffix'] = $iconSet['classSuffix'] ?? '';

            $this->iconSets[$iconSetName] = $iconSet;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_icon', [$this, 'getIcon'], ['pre_escape' => 'html', 'is_safe' => ['html']]),
        ];
    }

    /**
     * Get the an icomoon icon based on the configuration.
     *
     * @param string $icon
     * @param string $className
     * @param string $iconSetName
     *
     * @return string
     */
    public function getIcon(string $icon, ?string $className = '', string $iconSetName = 'default'): string
    {
        $iconSet = $this->getIconSet($iconSetName);

        $className = trim(sprintf(
            '%s %s %s%s%s',
            $className ?: '',
            $iconSet['className'],
            $iconSet['classPrefix'],
            $icon,
            $iconSet['classSuffix']
        ));

        if (self::ICON_SET_TYPE_FONT === $iconSet['type']) {
            return sprintf('<span class="%s"></span>', $className);
        }

        return sprintf(
            '<svg class="%s"><use xlink:href="%s#%s"></use></svg>',
            $className,
            $iconSet['path'],
            $icon
        );
    }

    /**
     * @return mixed[]
     */
    private function getIconSet(string $name): array
    {
        if (!isset($this->iconSets[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Icon Set with name "%s" not found, found: "%s".',
                $name,
                implode('", "', array_keys($this->iconSets))
            ));
        }

        return $this->iconSets[$name];
    }
}
