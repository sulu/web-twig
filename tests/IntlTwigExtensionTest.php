<?php

use Massive\Component\Web\IntlTwigExtension;
use PHPUnit\Framework\TestCase;

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

    public function testLocalize()
    {
        $this->assertEquals(
            'de_AT',
            $this->intlTwigExtension->getIcuLocale('de-at')
        );

        $this->assertEquals(
            'de',
            $this->intlTwigExtension->getIcuLocale('de')
        );
    }

    public function testCountry()
    {
        $this->assertEquals(
            'Germany',
            $this->intlTwigExtension->getCountry('de', 'en')
        );

        $this->assertEquals(
            'Deutschland',
            $this->intlTwigExtension->getCountry('de', 'de')
        );
    }

    public function testLanguage()
    {
        $this->assertEquals(
            'German',
            $this->intlTwigExtension->getLanguage('de', null, 'en')
        );

        $this->assertEquals(
            'Deutsch',
            $this->intlTwigExtension->getLanguage('de', null, 'de')
        );

        $this->assertEquals(
            'Austrian German',
            $this->intlTwigExtension->getLanguage('de', 'AT', 'en')
        );

        $this->assertEquals(
            'Österreichisches Deutsch',
            $this->intlTwigExtension->getLanguage('de', 'AT', 'de')
        );
    }

    public function testLocale()
    {
        $this->assertEquals(
            'Deutsch',
            $this->intlTwigExtension->getLocale('de', 'de')
        );

        $this->assertEquals(
            'German',
            $this->intlTwigExtension->getLocale('de', 'en')
        );

        $this->assertEquals(
            'Deutsch (Österreich)',
            $this->intlTwigExtension->getLocale('de_AT', 'de')
        );

        $this->assertEquals(
            'German (Austria)',
            $this->intlTwigExtension->getLocale('de_AT', 'en')
        );
    }
}
