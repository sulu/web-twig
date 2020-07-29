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

use Sulu\Twig\Extensions\TokenParser\PortalTokenParser;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class PortalExtension extends AbstractExtension
{
    /**
     * @var mixed[]
     */
    private static $PORTALS = [];

    public function getTokenParsers()
    {
        return [new PortalTokenParser()];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_portal', [$this, 'getPortal'], ['is_safe' => ['all']]),
        ];
    }

    public function getPortal(string $name): string
    {
        if (!isset(self::$PORTALS[$name])) {
            return '';
        }

        $output = '';

        foreach (self::$PORTALS[$name] as $portalContent) {
            $output .= $portalContent;
        }

        unset(self::$PORTALS[$name]);

        return $output;
    }

    /**
     * @internal
     *
     * @param string $name
     * @param string|Markup $body
     */
    public static function addPortal($name, $body): void
    {
        self::$PORTALS[$name][] = $body;
    }
}
