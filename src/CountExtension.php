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

class CountExtension extends AbstractExtension
{
    /**
     * @var int[]
     */
    private $counters = [];

    public function getFunctions()
    {
        return [
            new TwigFunction('counter', [$this, 'increaseCounter']),
            new TwigFunction('reset_counter', [$this, 'resetCounter']),
            new TwigFunction('get_counter', [$this, 'getCounter']),
        ];
    }

    public function increaseCounter(string $group): int
    {
        if (!isset($this->counters[$group])) {
            $this->counters[$group] = 0;
        }

        return ++$this->counters[$group];
    }

    public function resetCounter(string $group): void
    {
        $this->counters[$group] = 0;
    }

    public function getCounter(string $group): int
    {
        return isset($this->counters[$group]) ? $this->counters[$group] : 0;
    }
}
