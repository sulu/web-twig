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

namespace Sulu\Twig\Extensions\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sulu\Twig\Extensions\PortalExtension;

class PortalExtensionTest extends TestCase
{
    /**
     * @var PortalExtension
     */
    private $portalExtension;

    public function setUp(): void
    {
        $this->portalExtension = new PortalExtension();
    }

    public function testGetPortal(): void
    {
        $this->portalExtension::addPortal('test', 'Hello World');

        $this->assertSame(
            'Hello World',
            $this->portalExtension->getPortal('test')
        );

        // read again should return nothing to avoid template side effects
        $this->assertSame(
            '',
            $this->portalExtension->getPortal('test')
        );
    }

    public function testGetNotExistPortal(): void
    {
        $this->assertSame(
            '',
            $this->portalExtension->getPortal('not-exist')
        );
    }
}
