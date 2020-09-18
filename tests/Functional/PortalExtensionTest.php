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

use Sulu\Twig\Extensions\PortalExtension;

class PortalExtensionTest extends BaseFunctionalTestCase
{
    public function testPortal(): void
    {
        $twig = $this->getTwig();
        $twig->addExtension(new PortalExtension());
        $this->assertSame(
            file_get_contents(__DIR__ . '/snapshot/portal.txt'),
            $twig->render('portal/portal.html.twig')
        );
    }
}
