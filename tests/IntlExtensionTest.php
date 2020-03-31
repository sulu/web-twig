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
use Symfony\Component\Intl\Languages;

class IntlExtensionTest extends TestCase
{
    /**
     * @var IntlExtension
     */
    private $intlExtension;

    public function setUp(): void
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

    public function testAlpha3Country(): void
    {
        $this->assertSame(
            'Germany',
            $this->intlExtension->getAlpha3Country('deu', 'en')
        );

        $this->assertSame(
            'Deutschland',
            $this->intlExtension->getAlpha3Country('deu', 'de')
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

    public function testAlpha3Countries(): void
    {
        $this->assertContains(
            'Germany',
            $this->intlExtension->getAlpha3Countries('en')
        );

        $this->assertContains(
            'Deutschland',
            $this->intlExtension->getAlpha3Countries('de')
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
    }

    public function testLanguageWithRegion(): void
    {
        if (class_exists(Languages::class)) {
            // See https://github.com/symfony/symfony/issues/35309
            $this->markTestSkipped('Languages with region is since symfony 4.4 not longer possible');
        }

        $this->assertSame(
            'Austrian German',
            $this->intlExtension->getLanguage('en', 'GB', 'en')
        );

        $this->assertSame(
            'Österreichisches Deutsch',
            $this->intlExtension->getLanguage('de', 'AT', 'de')
        );
    }

    public function testAlpha3Language(): void
    {
        $this->assertSame(
            'German',
            $this->intlExtension->getAlpha3Language('deu', 'en')
        );

        $this->assertSame(
            'Deutsch',
            $this->intlExtension->getAlpha3Language('deu', 'de')
        );
    }

    public function testLanguages(): void
    {
        $this->assertContains(
            'German',
            $this->intlExtension->getLanguages('en')
        );

        $this->assertContains(
            'Deutsch',
            $this->intlExtension->getLanguages('de')
        );
    }

    public function testLanguagesWithRegions(): void
    {
        if (class_exists(Languages::class)) {
            // See https://github.com/symfony/symfony/issues/35309
            $this->markTestSkipped('Languages with region is since symfony 4.4 not longer possible');
        }

        $this->assertContains(
            'Austrian German',
            $this->intlExtension->getLanguages('en')
        );

        $this->assertContains(
            'Österreichisches Deutsch',
            $this->intlExtension->getLanguages('de')
        );
    }

    public function testAlpha3Languages(): void
    {
        $this->assertContains(
            'German',
            $this->intlExtension->getAlpha3Languages('en')
        );

        $this->assertContains(
            'Deutsch',
            $this->intlExtension->getAlpha3Languages('de')
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
