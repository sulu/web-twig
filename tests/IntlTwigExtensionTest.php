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
use Sulu\Twig\Extensions\IntlTwigExtension;

class IntlTwigExtensionTest extends TestCase
{
    /**
     * @var IntlTwigExtension
     */
    private $intlTwigExtension;

    public function setup()
    {
        $this->intlTwigExtension = new IntlTwigExtension();
    }

    public function testLocalize(): void
    {
        $this->assertSame(
            'de_AT',
            $this->intlTwigExtension->getIcuLocale('de-at')
        );

        $this->assertSame(
            'de',
            $this->intlTwigExtension->getIcuLocale('de')
        );
    }

    public function testCountry(): void
    {
        $this->assertSame(
            'Germany',
            $this->intlTwigExtension->getCountry('de', 'en')
        );

        $this->assertSame(
            'Deutschland',
            $this->intlTwigExtension->getCountry('de', 'de')
        );
    }

    public function testCountries(): void
    {
        $this->assertContains(
            'Germany',
            $this->intlTwigExtension->getCountries('en')
        );

        $this->assertContains(
            'Deutschland',
            $this->intlTwigExtension->getCountries('de')
        );
    }

    public function testLanguage(): void
    {
        $this->assertSame(
            'German',
            $this->intlTwigExtension->getLanguage('de', null, 'en')
        );

        $this->assertSame(
            'Deutsch',
            $this->intlTwigExtension->getLanguage('de', null, 'de')
        );

        $this->assertSame(
            'Austrian German',
            $this->intlTwigExtension->getLanguage('de', 'AT', 'en')
        );

        $this->assertSame(
            'Österreichisches Deutsch',
            $this->intlTwigExtension->getLanguage('de', 'AT', 'de')
        );
    }

    public function testLanguages(): void
    {
        $this->assertContains(
            'German',
            $this->intlTwigExtension->getLanguages('en')
        );

        $this->assertContains(
            'Austrian German',
            $this->intlTwigExtension->getLanguages('en')
        );

        $this->assertContains(
            'Deutsch',
            $this->intlTwigExtension->getLanguages('de')
        );

        $this->assertContains(
            'Österreichisches Deutsch',
            $this->intlTwigExtension->getLanguages('de')
        );
    }

    public function testLocale(): void
    {
        $this->assertSame(
            'Deutsch',
            $this->intlTwigExtension->getLocale('de', 'de')
        );

        $this->assertSame(
            'German',
            $this->intlTwigExtension->getLocale('de', 'en')
        );

        $this->assertSame(
            'Deutsch (Österreich)',
            $this->intlTwigExtension->getLocale('de_AT', 'de')
        );

        $this->assertSame(
            'German (Austria)',
            $this->intlTwigExtension->getLocale('de_AT', 'en')
        );
    }

    public function testLocales(): void
    {
        $this->assertContains(
            'Deutsch',
            $this->intlTwigExtension->getLocales('de')
        );

        $this->assertContains(
            'German',
            $this->intlTwigExtension->getLocales('en')
        );

        $this->assertContains(
            'Deutsch (Österreich)',
            $this->intlTwigExtension->getLocales('de')
        );

        $this->assertContains(
            'German (Austria)',
            $this->intlTwigExtension->getLocales('en')
        );
    }
}
