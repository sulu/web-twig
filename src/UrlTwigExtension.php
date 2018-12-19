<?php

namespace Massive\Component\Web;

/**
 * This Twig Extension manages url transformation.
 */
class UrlTwigExtension extends \Twig_Extension
{
    const DEFAULT_PROTOCOL = 'http';

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('url_sanitize', [$this, 'sanitizeUrl']),
            new \Twig_SimpleFilter('url_protocol', [$this, 'getProtocol']),
            new \Twig_SimpleFilter('url_host', [$this, 'getHost']),
            new \Twig_SimpleFilter('url_port', [$this, 'getPort']),
            new \Twig_SimpleFilter('url_domain', [$this, 'getDomain']),
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
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private static function parseUrl($url)
    {
        $parsedUrl = parse_url($url);

        if (false === $parsedUrl) {
            throw new \InvalidArgumentException(sprintf('URL (%s) is malformed!', $url));
        }

        return $parsedUrl;
    }

    /**
     * Sanitizes url.
     *
     * @param $url string
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function sanitizeUrl($url)
    {
        $sanitizedUrl = filter_var($url, FILTER_SANITIZE_URL);

        $parsedUrl = self::parseUrl($sanitizedUrl);

        $result = '';

        if (isset($parsedUrl['scheme']) && $parsedUrl['scheme']) {
            $result .= $parsedUrl['scheme'] . '://';
        }

        if (isset($parsedUrl['user']) && $parsedUrl['user']) {
            $result .= $parsedUrl['user'];

            if (isset($parsedUrl['pass']) && $parsedUrl['pass']) {
                $result .= ':' . $parsedUrl['pass'];
            }
        }

        if (isset($parsedUrl['host']) && $parsedUrl['host']) {
            $result .= $parsedUrl['host'];

            if (isset($parsedUrl['port']) && $parsedUrl['port']) {
                $result .= ':' . $parsedUrl['port'];
            }
        }

        if (isset($parsedUrl['path']) && $parsedUrl['path']) {
            $result .= $parsedUrl['path'];
        }

        if (isset($parsedUrl['query']) && $parsedUrl['query']) {
            $result .= '?' . $parsedUrl['query'];
        }

        if (isset($parsedUrl['fragment']) && $parsedUrl['fragment']) {
            $result .= '#' . $parsedUrl['fragment'];
        }

        return $result;
    }

    /**
     * Get protocol of url.
     *
     * @param $url string
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function getProtocol($url)
    {
        $parsedUrl = self::parseUrl($url);

        return isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] : null;
    }

    /**
     * Get host of url.
     *
     * @param $url string
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function getHost($url)
    {
        $parsedUrl = self::parseUrl($url);

        return isset($parsedUrl['host']) ? $parsedUrl['host'] : null;
    }

    /**
     * Get port of url.
     *
     * @param $url string
     *
     * @return int|null
     *
     * @throws \InvalidArgumentException
     */
    public function getPort($url)
    {
        $parsedUrl = self::parseUrl($url);

        return isset($parsedUrl['port']) ? $parsedUrl['port'] : null;
    }

    /**
     * Get domain of url.
     *
     * @param $url string
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function getDomain($url)
    {
        $parsedUrl = self::parseUrl($url);

        if (!isset($parsedUrl['scheme']) && !isset($parsedUrl['host']) && !preg_match('/^[^\/\?#]/', $url)) {
            $parsedUrl = self::parseUrl(self::DEFAULT_PROTOCOL . '://' . $url);
        }

        $result = implode(':', array_filter([
            isset($parsedUrl['host']) ? $parsedUrl['host'] : '',
            isset($parsedUrl['port']) ? $parsedUrl['port'] : '',
        ]));

        return $result ? $result : null;
    }

    /**
     * Get path of url.
     *
     * @param $url string
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function getPath($url)
    {
        $parsedUrl = self::parseUrl($url);

        return isset($parsedUrl['path']) ? $parsedUrl['path'] : null;
    }

    /**
     * Get query of url.
     *
     * @param $url string
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function getQuery($url)
    {
        $parsedUrl = self::parseUrl($url);

        return isset($parsedUrl['query']) ? $parsedUrl['query'] : null;
    }

    /**
     * Get fragment of url.
     *
     * @param $url string
     *
     * @return string|null
     *
     * @throws \InvalidArgumentException
     */
    public function getFragment($url)
    {
        $parsedUrl = self::parseUrl($url);

        return isset($parsedUrl['fragment']) ? $parsedUrl['fragment'] : null;
    }
}
