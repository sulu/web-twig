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

use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Intl\Locales;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * This Twig Extension manages the image formats.
 */
class IntlExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('intl_countries', [$this, 'getCountries']),
            new TwigFunction('intl_alpha3_countries', [$this, 'getAlpha3Countries']),
            new TwigFunction('intl_country', [$this, 'getCountry']),
            new TwigFunction('intl_alpha3_country', [$this, 'getAlpha3Country']),
            new TwigFunction('intl_locales', [$this, 'getLocales']),
            new TwigFunction('intl_locale', [$this, 'getLocale']),
            new TwigFunction('intl_languages', [$this, 'getLanguages']),
            new TwigFunction('intl_language', [$this, 'getLanguage']),
            new TwigFunction('intl_alpha3_languages', [$this, 'getAlpha3Languages']),
            new TwigFunction('intl_alpha3_language', [$this, 'getAlpha3Language']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('intl_icu_locale', [$this, 'getIcuLocale']),
        ];
    }

    /**
     * Get icu locale from sulu locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getIcuLocale(string $locale): string
    {
        $parts = explode('-', $locale);
        if (isset($parts[1])) {
            $parts[1] = mb_strtoupper($parts[1]);
        }

        return implode('_', $parts);
    }

    /**
     * Get countries.
     *
     * @param string|null $displayLocale
     *
     * @return string[]
     */
    public function getCountries(?string $displayLocale = null): array
    {
        if (class_exists(Countries::class)) {
            return Countries::getNames($displayLocale);
        }

        return Intl::getRegionBundle()->getCountryNames($displayLocale);
    }

    /**
     * Get alpha3 countries.
     *
     * @param string|null $displayLocale
     *
     * @return string[]
     */
    public function getAlpha3Countries(?string $displayLocale = null): array
    {
        return Countries::getAlpha3Names($displayLocale);
    }

    /**
     * Get country.
     *
     * @param string $country
     * @param string|null $displayLocale
     *
     * @return string|null
     */
    public function getCountry(string $country, ?string $displayLocale = null): ?string
    {
        if (class_exists(Countries::class)) {
            return Countries::getName(mb_strtoupper($country), $displayLocale);
        }

        return Intl::getRegionBundle()->getCountryName(mb_strtoupper($country), $displayLocale);
    }

    /**
     * Get alpha3 country.
     *
     * @param string $alpha3Code
     * @param string|null $displayLocale
     *
     * @return string|null
     */
    public function getAlpha3Country(string $alpha3Code, ?string $displayLocale = null): ?string
    {
        return Countries::getAlpha3Name(mb_strtoupper($alpha3Code), $displayLocale);
    }

    /**
     * Get languages.
     *
     * @param string|null $displayLocale
     *
     * @return string[]
     */
    public function getLanguages(?string $displayLocale = null): array
    {
        if (class_exists(Languages::class)) {
            return Languages::getNames($displayLocale);
        }

        return Intl::getLanguageBundle()->getLanguageNames($displayLocale);
    }

    /**
     * Get alpha3 languages.
     *
     * @param string|null $displayLocale
     *
     * @return string[]
     */
    public function getAlpha3Languages(?string $displayLocale = null): array
    {
        return Languages::getAlpha3Names($displayLocale);
    }

    /**
     * Get language.
     *
     * @param string $language
     * @param string|null $region
     * @param string|null $displayLocale
     *
     * @return string|null
     */
    public function getLanguage(string $language, ?string $region = null, ?string $displayLocale = null): ?string
    {
        if (class_exists(Languages::class)) {
            return Languages::getName($language, $displayLocale);
        }

        return Intl::getLanguageBundle()->getLanguageName($language, $region, $displayLocale);
    }

    /**
     * Get alpha3 language.
     *
     * @param string $language
     * @param string|null $displayLocale
     *
     * @return string|null
     */
    public function getAlpha3Language(string $language, ?string $displayLocale = null): ?string
    {
        return Languages::getAlpha3Name($language, $displayLocale);
    }

    /**
     * Get locales.
     *
     * @param string|null $displayLocale
     *
     * @return string[]
     */
    public function getLocales(?string $displayLocale = null): array
    {
        if (class_exists(Locales::class)) {
            return Locales::getNames($displayLocale);
        }

        return Intl::getLocaleBundle()->getLocaleNames($displayLocale);
    }

    /**
     * Get locale.
     *
     * @param string $locale
     * @param string|null $displayLocale
     *
     * @return string|null
     */
    public function getLocale(string $locale, ?string $displayLocale = null): ?string
    {
        if (class_exists(Locales::class)) {
            return Locales::getName($locale, $displayLocale);
        }

        return Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale);
    }
}
