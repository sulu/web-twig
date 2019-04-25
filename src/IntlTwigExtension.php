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

namespace Sulu\Component\Web\Twig;

use Symfony\Component\Intl\Intl;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * This Twig Extension manages the image formats.
 */
class IntlTwigExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('intl_countries', [$this, 'getCountries']),
            new TwigFunction('intl_country', [$this, 'getCountry']),
            new TwigFunction('intl_locales', [$this, 'getLocales']),
            new TwigFunction('intl_locale', [$this, 'getLocale']),
            new TwigFunction('intl_languages', [$this, 'getLanguages']),
            new TwigFunction('intl_language', [$this, 'getLanguage']),
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
    public function getIcuLocale($locale): string
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
    public function getCountries($displayLocale = null): array
    {
        return Intl::getRegionBundle()->getCountryNames($displayLocale);
    }

    /**
     * Get country.
     *
     * @param string $country
     * @param string|null $displayLocale
     *
     * @return string|null
     */
    public function getCountry($country, $displayLocale = null): ?string
    {
        return Intl::getRegionBundle()->getCountryName(mb_strtoupper($country), $displayLocale);
    }

    /**
     * Get languages.
     *
     * @param string|null $displayLocale
     *
     * @return string[]
     */
    public function getLanguages($displayLocale = null): array
    {
        return Intl::getLanguageBundle()->getLanguageNames($displayLocale);
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
    public function getLanguage($language, $region = null, $displayLocale = null): ?string
    {
        return Intl::getLanguageBundle()->getLanguageName($language, $region, $displayLocale);
    }

    /**
     * Get locales.
     *
     * @param string|null $displayLocale
     *
     * @return string[]
     */
    public function getLocales($displayLocale = null): array
    {
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
    public function getLocale($locale, $displayLocale = null): ?string
    {
        return Intl::getLocaleBundle()->getLocaleName($locale, $displayLocale);
    }
}
