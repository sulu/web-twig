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

namespace Sulu\Twig\Extensions\Tests\Functional;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class BaseFunctionalTestCase extends TestCase
{
    /**
     * @param array<string, bool> $options
     */
    protected function getTwig(array $options = []): Environment
    {
        return new Environment(
            new FilesystemLoader(__DIR__ . \DIRECTORY_SEPARATOR . 'templates'),
            array_merge([
                'debug' => true,
                'strict_variables' => true,
            ], $options)
        );
    }
}
