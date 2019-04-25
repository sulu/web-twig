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

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * This Twig Extension manages url transformation.
 */
class UrlTwigExtension extends AbstractExtension
{
    const DEFAULT_SCHEME = 'http';

    const SCHEME = 'scheme';
    const USER = 'user';
    const PASS = 'pass';
    const HOST = 'host';
    const PORT = 'port';
    const PATH = 'path';
    const QUERY = 'query';
    const FRAGMENT = 'fragment';

    const DEFAULT_FLAGS = [
        self::SCHEME => true,
        self::USER => true,
        self::PASS => true,
        self::HOST => true,
        self::PORT => true,
        self::PATH => true,
        self::QUERY => true,
        self::FRAGMENT => true,
    ];

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('url_format', [$this, 'formatUrl']),
            new TwigFilter('url_scheme', [$this, 'getScheme']),
            new TwigFilter('url_user', [$this, 'getUser']),
            new TwigFilter('url_pass', [$this, 'getPass']),
            new TwigFilter('url_host', [$this, 'getHost']),
            new TwigFilter('url_port', [$this, 'getPort']),
            new TwigFilter('url_path', [$this, 'getPath']),
            new TwigFilter('url_query', [$this, 'getQuery']),
            new TwigFilter('url_fragment', [$this, 'getFragment']),
        ];
    }

    /**
     * Get all components of url.
     *
     * @param string $url
     *
     * @return array|null
     */
    private static function parseUrl($url): ?array
    {
        $parsedUrl = parse_url($url);

        if (false === $parsedUrl) {
            return null;
        }

        return $parsedUrl;
    }

    /**
     * Returns true if all the required flags would return a valid value. Otherwise returns false.
     *
     * @param array|null $parsedUrl
     * @param array $flags
     * @param array ...$requiredFlags
     *
     * @return bool
     */
    private static function validFlag($parsedUrl, $flags, ...$requiredFlags): bool
    {
        if (null === $parsedUrl) {
            return false;
        }

        foreach ($flags as $flag => $active) {
            if (\in_array($flag, $requiredFlags, true) && (!$active || !isset($parsedUrl[$flag]) || !$parsedUrl[$flag])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Formats url based on flags. All flags are true by default.
     *
     * <code>
     * $flags = array(
     *     'scheme' => true,
     *     'user' => true,
     *     'pass' => true,
     *     'host' => true,
     *     'port' => true,
     *     'path' => true,
     *     'query' => true,
     *     'fragment' => true,
     * );
     *
     * </code>
     *
     * @param string $url
     * @param array $flags
     *
     * @return string|null
     */
    public function formatUrl($url, $flags = []): ?string
    {
        $parsedUrl = self::parseUrl($url);

        if (null === $parsedUrl) {
            return null;
        }

        $flags = array_merge(self::DEFAULT_FLAGS, $flags);

        $result = '';

        foreach ($flags as $flag => $active) {
            if (!self::validFlag($parsedUrl, $flags, $flag)) {
                continue;
            }

            $value = $parsedUrl[$flag];

            switch ($flag) {
                case self::SCHEME:
                    /* Scheme requires host */
                    if (self::validFlag($parsedUrl, $flags, self::HOST)) {
                        $result .= $value . '://';
                    }

                    break;
                case self::USER:
                    /* User requires host */
                    if (self::validFlag($parsedUrl, $flags, self::HOST)) {
                        $result .= $value;
                    }

                    break;
                case self::PASS:
                    /* Pass requires host and user */
                    if (self::validFlag($parsedUrl, $flags, self::HOST, self::USER)) {
                        $result .= ':' . $value;
                    }

                    break;
                case self::HOST:
                    if (self::validFlag($parsedUrl, $flags, self::USER)) {
                        $result .= '@';
                    }

                    $result .= $value;

                    break;
                case self::PORT:
                    /* Port requires host */
                    if (self::validFlag($parsedUrl, $flags, self::HOST)) {
                        $result .= ':' . $value;
                    }

                    break;
                case self::PATH:
                    $result .= $parsedUrl[self::PATH];

                    break;
                case self::QUERY:
                    /* Query requires path */
                    if (self::validFlag($parsedUrl, $flags, self::PATH)) {
                        $result .= '?' . $value;
                    }

                    break;
                case self::FRAGMENT:
                    /* Fragment requires path */
                    if (self::validFlag($parsedUrl, $flags, self::PATH)) {
                        $result .= '#' . $value;
                    }

                    break;
            }
        }

        return $result ? $result : null;
    }

    /**
     * Get scheme of url.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getScheme($url): ?string
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::SCHEME]) ? $parsedUrl[self::SCHEME] : null;
    }

    /**
     * Get user of url.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getUser($url): ?string
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::USER]) ? $parsedUrl[self::USER] : null;
    }

    /**
     * Get password of url.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getPass($url): ?string
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::PASS]) ? $parsedUrl[self::PASS] : null;
    }

    /**
     * Get host of url.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getHost($url): ?string
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::HOST]) ? $parsedUrl[self::HOST] : null;
    }

    /**
     * Get port of url.
     *
     * @param string $url
     *
     * @return int|null
     */
    public function getPort($url): ?int
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::PORT]) ? $parsedUrl[self::PORT] : null;
    }

    /**
     * Get path of url.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getPath($url): ?string
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::PATH]) ? $parsedUrl[self::PATH] : null;
    }

    /**
     * Get query of url.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getQuery($url): ?string
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::QUERY]) ? $parsedUrl[self::QUERY] : null;
    }

    /**
     * Get fragment of url.
     *
     * @param string $url
     *
     * @return string|null
     */
    public function getFragment($url): ?string
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::FRAGMENT]) ? $parsedUrl[self::FRAGMENT] : null;
    }
}
