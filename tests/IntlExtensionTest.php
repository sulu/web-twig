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
use Sulu\Twig\Extensions\IntlExtension;

class IntlExtensionTest extends TestCase
{
    /**
     * @var IntlExtension
     */
    private $intlExtension;

    public function setup()
    {
        $this->intlExtension = new IntlExtension();
    }

    public function testLocalize(): void
    {
        $this->assertSame(
            'de_AT',
            $this->intlExtension->getIcuLocale('de-at')
        );

        $this->assertSame(
            'de',
            $this->intlExtension->getIcuLocale('de')
        );
    }

    public function testCountry(): void
    {
        $this->assertSame(
            'Germany',
            $this->intlExtension->getCountry('de', 'en')
        );

        $this->assertSame(
            'Deutschland',
            $this->intlExtension->getCountry('de', 'de')
        );
    }

    public function testCountries(): void
    {
        $this->assertContains(
            'Germany',
            $this->intlExtension->getCountries('en')
        );

        $this->assertContains(
            'Deutschland',
            $this->intlExtension->getCountries('de')
        );
    }

    public function testLanguage(): void
    {
        $this->assertSame(
            'German',
            $this->intlExtension->getLanguage('de', null, 'en')
        );

        $this->assertSame(
            'Deutsch',
            $this->intlExtension->getLanguage('de', null, 'de')
        );

        $this->assertSame(
            'Austrian German',
            $this->intlExtension->getLanguage('de', 'AT', 'en')
        );

        $this->assertSame(
            'Österreichisches Deutsch',
            $this->intlExtension->getLanguage('de', 'AT', 'de')
        );
    }

    public function testLanguages(): void
    {
        $this->assertContains(
            'German',
            $this->intlExtension->getLanguages('en')
        );

        $this->assertContains(
            'Austrian German',
            $this->intlExtension->getLanguages('en')
        );

        $this->assertContains(
            'Deutsch',
            $this->intlExtension->getLanguages('de')
        );

        $this->assertContains(
            'Österreichisches Deutsch',
            $this->intlExtension->getLanguages('de')
        );
    }

    public function testLocale(): void
    {
        $this->assertSame(
            'Deutsch',
            $this->intlExtension->getLocale('de', 'de')
        );

        $this->assertSame(
            'German',
            $this->intlExtension->getLocale('de', 'en')
        );

        $this->assertSame(
            'Deutsch (Österreich)',
            $this->intlExtension->getLocale('de_AT', 'de')
        );

        $this->assertSame(
            'German (Austria)',
            $this->intlExtension->getLocale('de_AT', 'en')
        );
    }

    public function testLocales(): void
    {
        $this->assertContains(
            'Deutsch',
            $this->intlExtension->getLocales('de')
        );

        $this->assertContains(
            'German',
            $this->intlExtension->getLocales('en')
        );

        $this->assertContains(
            'Deutsch (Österreich)',
            $this->intlExtension->getLocales('de')
        );

        $this->assertContains(
            'German (Austria)',
            $this->intlExtension->getLocales('en')
        );
    }
}
