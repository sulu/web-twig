<?php

namespace Massive\Component\Web;

/**
 * This Twig Extension manages url transformation.
 */
class UrlTwigExtension extends \Twig_Extension
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
            new \Twig_SimpleFilter('url_format', [$this, 'formatUrl']),
            new \Twig_SimpleFilter('url_scheme', [$this, 'getScheme']),
            new \Twig_SimpleFilter('url_host', [$this, 'getHost']),
            new \Twig_SimpleFilter('url_port', [$this, 'getPort']),
            new \Twig_SimpleFilter('url_path', [$this, 'getPath']),
            new \Twig_SimpleFilter('url_query', [$this, 'getQuery']),
            new \Twig_SimpleFilter('url_fragment', [$this, 'getFragment']),
        ];
    }

    /**
     * Get all components of url.
     *
     * @param $url string
     *
     * @return array|null
     */
    private static function parseUrl($url)
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
    private static function validFlag($parsedUrl, $flags, ...$requiredFlags)
    {
        if (null === $parsedUrl) {
            return false;
        }

        foreach ($flags as $flag => $active) {
            if (in_array($flag, $requiredFlags) && (!$active || !isset($parsedUrl[$flag]) || !$parsedUrl[$flag])) {
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
     * @param $url string
     * @param $flags array|null
     *
     * @return string|null
     */
    public function formatUrl($url, $flags = [])
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
     * @param $url string
     *
     * @return string|null
     */
    public function getScheme($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::SCHEME]) ? $parsedUrl[self::SCHEME] : null;
    }

    /**
     * Get user of url.
     *
     * @param $url string
     *
     * @return string|null
     */
    public function getUser($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::USER]) ? $parsedUrl[self::USER] : null;
    }

    /**
     * Get password of url.
     *
     * @param $url string
     *
     * @return string|null
     */
    public function getPass($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::PASS]) ? $parsedUrl[self::PASS] : null;
    }

    /**
     * Get host of url.
     *
     * @param $url string
     *
     * @return string|null
     */
    public function getHost($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::HOST]) ? $parsedUrl[self::HOST] : null;
    }

    /**
     * Get port of url.
     *
     * @param $url string
     *
     * @return int|null
     */
    public function getPort($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::PORT]) ? $parsedUrl[self::PORT] : null;
    }

    /**
     * Get path of url.
     *
     * @param $url string
     *
     * @return string|null
     */
    public function getPath($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::PATH]) ? $parsedUrl[self::PATH] : null;
    }

    /**
     * Get query of url.
     *
     * @param $url string
     *
     * @return string|null
     */
    public function getQuery($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::QUERY]) ? $parsedUrl[self::QUERY] : null;
    }

    /**
     * Get fragment of url.
     *
     * @param $url string
     *
     * @return string|null
     */
    public function getFragment($url)
    {
        $parsedUrl = self::parseUrl($url);

        return null !== $parsedUrl && isset($parsedUrl[self::FRAGMENT]) ? $parsedUrl[self::FRAGMENT] : null;
    }
}
