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

use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * This Twig Extension manages the image formats.
 */
class ImageExtension extends AbstractExtension
{
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var string|null
     */
    protected $placeholderPath;

    /**
     * @var bool
     */
    protected $hasLazyImage = false;

    /**
     * @var string[]
     */
    private $placeholders = null;

    /**
     * @var string[]
     */
    private $defaultAttributes = null;

    /**
     * @var string[]
     */
    private $defaultAdditionalTypes = [];

    /**
     * @var string[]
     */
    private $ignoreTypesMimeTypes = [
        // the official svg mimetype when the <?xml header exist
        'image/svg+xml',
        // if the <?xml header does not exist this is the mimetype returned by php:
        // https://bugs.php.net/bug.php?id=79045
        // https://3v4l.org/0h5jI
        'image/svg',
    ];

    /**
     * @var bool
     */
    private $aspectRatio = false;

    /**
     * @var array<string, array{
     *     scale: array{
     *         x: int|null,
     *         y: int|null,
     *         mode: int,
     *         retina: bool,
     *     },
     * }>|null
     */
    private $imageFormatConfiguration = null;

    /**
     * @param string[] $defaultAttributes
     * @param string[] $defaultAdditionalTypes
     * @param array<string, array{
     *     scale: array{
     *         x: int|null,
     *         y: int|null,
     *         mode: int,
     *         retina: bool,
     *     },
     * }>|null $imageFormatConfiguration
     */
    public function __construct(
        ?string $placeholderPath = null,
        array $defaultAttributes = [],
        array $defaultAdditionalTypes = [],
        bool $aspectRatio = false,
        array $imageFormatConfiguration = null
    ) {
        if (null !== $placeholderPath) {
            $this->placeholderPath = rtrim($placeholderPath, '/') . '/';
        }

        $this->defaultAttributes = $defaultAttributes;
        $this->defaultAdditionalTypes = $defaultAdditionalTypes;
        $this->aspectRatio = $aspectRatio;
        $this->imageFormatConfiguration = $imageFormatConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_image', [$this, 'getImage'], ['is_safe' => ['html']]),
            new TwigFunction('get_lazy_image', [$this, 'getLazyImage'], ['is_safe' => ['html']]),
            new TwigFunction('has_lazy_image', [$this, 'hasLazyImage']),
        ];
    }

    /**
     * Get an image or picture tag with given attributes for lazy loading.
     *
     * @param mixed $media
     * @param array<string, string|null>|string $attributes
     * @param mixed[] $sources
     * @param mixed[] $additionalTypes
     *
     * @return string
     */
    public function getLazyImage($media, $attributes = [], array $sources = [], array $additionalTypes = []): string
    {
        if (null === $this->placeholderPath) {
            throw new \InvalidArgumentException(
                'You need to define placeholderPaths constructor argument to use the get_lazy_image function'
            );
        }

        if (\is_array($media)) {
            $media = (object) $media;
        }

        $thumbnails = $this->getPropertyAccessor()->getValue($media, 'thumbnails');
        $this->hasLazyImage = true;

        return $this->createImage($media, $attributes, $sources, $this->getLazyThumbnails($thumbnails), $additionalTypes);
    }

    /**
     * Get lazy image was called once or more times.
     *
     * @return bool
     */
    public function hasLazyImage(): bool
    {
        return $this->hasLazyImage;
    }

    /**
     * Get an image or picture tag with given attributes.
     *
     * @param mixed $media
     * @param array<string, string|null>|string $attributes
     * @param mixed[] $sources
     * @param mixed[] $additionalTypes
     *
     * @return string
     */
    public function getImage($media, $attributes = [], array $sources = [], array $additionalTypes = []): string
    {
        return $this->createImage($media, $attributes, $sources, null, $additionalTypes);
    }

    /**
     * Get an image or picture tag with given attributes.
     *
     * @param mixed $media
     * @param array<string, string|null>|string $attributes
     * @param mixed[] $sources
     * @param string[]|null $lazyThumbnails
     * @param string[] $additionalTypes
     *
     * @return string
     */
    private function createImage(
        $media,
        $attributes = [],
        array $sources = [],
        ?array $lazyThumbnails = null,
        array $additionalTypes = []
    ): string {
        // Return an empty string if no one of the needed parameters is set.
        if (empty($media) || empty($attributes)) {
            return '';
        }

        if (\is_array($media)) {
            $media = (object) $media;
        }

        $propertyAccessor = $this->getPropertyAccessor();

        // Thumbnails exists all times - it only can be empty.
        $thumbnails = $propertyAccessor->getValue($media, 'thumbnails');

        // If attributes is an string, convert it to an array (like '{{ get_image_tag(media, '650x') }}').
        if (\is_string($attributes)) {
            $attributes = [
                'src' => $attributes,
            ];
        }

        // The default attributes and attributes are merged together and not replaced
        /** @var array<string, string|null> $attributes */
        $attributes = array_merge(
            $this->defaultAttributes,
            $attributes
        );

        if ($this->aspectRatio && isset($attributes['src']) && !isset($attributes['width']) && !isset($attributes['height'])) {
            list($width, $height) = $this->guessAspectRatio($media, $attributes);

            $attributes['width'] = ((string) $width) ?: null;
            $attributes['height'] = ((string) $height) ?: null;
        }

        // The default additional types and additional types are merged together and not replaced
        /** @var string[] $additionalTypes */
        $additionalTypes = array_merge(
            $this->defaultAdditionalTypes,
            $additionalTypes
        );

        // Get MimeType from Media
        $mimeType = $this->getMimeType($media);

        // Add src and srcset configuration as additional types at end of source tags
        if (!\in_array($mimeType, $this->ignoreTypesMimeTypes, true)) {
            foreach ($additionalTypes as $extension => $type) {
                $srcset = null;

                if (isset($attributes['src']) && (!isset($attributes['srcset']) || false === strpos($attributes['srcset'], $attributes['src'] . ' '))) {
                    $srcset = $this->addExtension($attributes['src'], $extension);
                }

                if (isset($attributes['srcset'])) {
                    if ($srcset) {
                        $srcset .= ', ';
                    }

                    $srcset .= $this->addExtension($attributes['srcset'], $extension);
                }

                if ($srcset) {
                    $sources[] = [
                        'srcset' => $srcset,
                        'sizes' => $attributes['sizes'] ?? null,
                        'type' => $type,
                    ];
                }
            }
        }

        if ($lazyThumbnails) {
            $attributes['class'] = trim((isset($attributes['class']) ? $attributes['class'] : '') . ' lazyload');
        }

        // Get title from object to use as alt attribute.
        if ($propertyAccessor->isReadable($media, 'title')) {
            $alt = $propertyAccessor->getValue($media, 'title');
        } else {
            // Get filename from thumbnail and use that.
            $alt = pathinfo(reset($thumbnails))['filename'] ?? '';
        }

        // Get description from object to use as title attribute else fallback to alt attribute.
        $title = $alt;
        if ($propertyAccessor->isReadable($media, 'description')) {
            $title = $propertyAccessor->getValue($media, 'description') ?: $alt;
        }

        /** @var array<string, string|null> $attributes */
        $attributes = array_merge(['alt' => $alt, 'title' => $title], $attributes);

        // Get the image tag with all given attributes.
        $imgTag = $this->createTag(
            'img',
            $attributes,
            $thumbnails,
            $lazyThumbnails
        );

        if (empty($sources)) {
            return $imgTag;
        }

        $sourceTags = '';
        foreach ($sources as $media => $sourceAttributes) {
            if (\is_string($sourceAttributes)) {
                $sourceAttributes = [
                    'srcset' => $sourceAttributes,
                ];
            }

            if (!isset($sourceAttributes['media']) && \is_string($media)) {
                $sourceAttributes = array_merge(['media' => $media], $sourceAttributes);
            }

            if (!\in_array($mimeType, $this->ignoreTypesMimeTypes, true)) {
                // Type specific source tag should be rendered before untyped
                foreach ($additionalTypes as $extension => $type) {
                    // avoid duplicated output of the same type
                    if (!isset($sourceAttributes['type']) || $sourceAttributes['type'] !== $type) {
                        $sourceTags .= $this->createTag(
                            'source',
                            array_merge($sourceAttributes, [
                                'srcset' => $this->addExtension($sourceAttributes['srcset'], $extension),
                                'type' => $type,
                            ]),
                            $thumbnails,
                            $lazyThumbnails
                        );
                    }
                }
            }

            // Get the source tag with all given attributes.
            $sourceTags .= $this->createTag(
                'source',
                $sourceAttributes,
                $thumbnails,
                $lazyThumbnails
            );
        }

        // Returns the picture tag with all sources and the fallback image tag.
        return sprintf('<picture>%s%s</picture>', $sourceTags, $imgTag);
    }

    /**
     * Create html tag.
     *
     * @param string $tag
     * @param array<string, string|null> $attributes
     * @param string[] $thumbnails
     * @param string[]|null $lazyThumbnails
     *
     * @return string
     */
    private function createTag(string $tag, array $attributes, array $thumbnails, ?array $lazyThumbnails = null): string
    {
        $output = '';

        foreach ($attributes as $key => $value) {
            // Ignore properties which are set to null e.g.: { loading: null }
            // This is used to remove default attributes from
            if (null === $value) {
                continue;
            }

            if ('src' === $key) {
                if ($lazyThumbnails) {
                    $output .= sprintf(' %s="%s"', $key, $lazyThumbnails[$value]);
                    $key = 'data-src';
                }

                // Set the thumbnail instead of image itself.
                $value = $thumbnails[$value];
            } elseif ('srcset' === $key) {
                if ($lazyThumbnails) {
                    $output .= sprintf(
                        ' %s="%s"',
                        $key,
                        htmlentities($this->srcsetThumbnailReplace($value, $lazyThumbnails))
                    );
                    $key = 'data-srcset';
                }

                // Replace thumbnail format in srcset.
                $value = $this->srcsetThumbnailReplace($value, $thumbnails);
            } elseif ('loading' === $key && 'auto' === $value) {
                continue;
            }

            $output .= sprintf(' %s="%s"', $key, htmlentities($value));
        }

        return sprintf('<%s%s>', $tag, $output);
    }

    /**
     * Replace the given image format with an thumbnail.
     *
     * @param string $value
     * @param string[] $thumbnails
     *
     * @return string
     */
    private function srcsetThumbnailReplace(string $value, array $thumbnails): string
    {
        // Split string to an array (to get each srcset).
        $srcSets = explode(',', $value);

        $newSrcSets = [];
        foreach ($srcSets as $srcSet) {
            // Split the values of an srcset to an array to get the thumbnail.
            $values = explode(' ', trim($srcSet), 2);

            // Set the thumbnail.
            $newSrcSet = $thumbnails[$values[0]];

            // Merge the thumbnail again with the width from srcset.
            if (isset($values[1])) {
                $newSrcSet .= ' ' . $values[1];
            }

            $newSrcSets[] = $newSrcSet;
        }

        return implode(', ', $newSrcSets);
    }

    /**
     * Get lazy thumbnails.
     *
     * @param array<string, string> $thumbnails
     *
     * @return string[]|null
     */
    private function getLazyThumbnails(array $thumbnails): ?array
    {
        if (empty($thumbnails)) {
            return null;
        }

        if (null === $this->placeholders) {
            $placeholders = [];
            foreach (array_keys($thumbnails) as $key) {
                $placeholders[$key] = $this->placeholderPath . $this->removeExtensionFromFormat($key) . '.svg';
            }

            $this->placeholders = $placeholders;
        }

        return $this->placeholders;
    }

    /**
     * @param mixed $media
     * @param array<string, string|null> $attributes
     *
     * @return array{
     *     0: int|null,
     *     1: int|null,
     * }
     */
    private function guessAspectRatio($media, array $attributes): array
    {
        $src = $attributes['src'] ?? '';

        if ($this->imageFormatConfiguration) {
            $scale = $this->imageFormatConfiguration[$src]['scale']['retina'] ? 2 : 1;
            $isInset = \in_array($this->imageFormatConfiguration[$src]['scale']['mode'], [
                1,
                'inset',
            ], true);
            $x = $this->imageFormatConfiguration[$src]['scale']['x'];
            $y = $this->imageFormatConfiguration[$src]['scale']['y'];
            $width = $x ? (int) round($x * $scale) : null;
            $height = $y ? (int) round($y * $scale) : null;
        } else {
            /*
             * This will extract the format dimension in all common formats.
             * @see ImageExtensionTest::testGuessAspectRatio
             */
            preg_match('/(\d+)?x(\d+)?(-inset)?(@)?(\d)?(x)?/', $src, $matches);

            $scale = !empty($matches[5]) ? (float) $matches[5] : 1;
            $width = !empty($matches[1]) ? (int) round($matches[1] * $scale) : null;
            $height = !empty($matches[2]) ? (int) round($matches[2] * $scale) : null;
            $isInset = !empty($matches[3]);
        }

        // fixed formats can directly be returned
        if ($width && $height && !$isInset) {
            return [$width, $height];
        }

        try {
            $properties = $this->getPropertyAccessor()->getValue($media, 'properties');
        } catch (AccessException $e) {
            $properties = [];
        }

        $originalWidth = $properties['width'] ?? null;
        $originalHeight = $properties['height'] ?? null;

        if (!$originalWidth || !$originalHeight) {
            return [null, null];
        }

        if ($isInset && $width && $height) {
            // calculate inset width and height e.g. 200x50-inset, 100x100-inset
            $insetWidth = $width;
            $insetHeight = $height;
            if ($originalWidth > $width) {
                $insetHeight = $originalHeight / $originalWidth * $width;
            }

            if (round($insetHeight) > $height) {
                $insetHeight = $height;
                $insetWidth = $originalWidth / $originalHeight * $height;
            }

            return [(int) round($insetWidth), (int) round($insetHeight)];
        }

        // calculate the not given dimension parameter
        if (!$width) {
            $width = $originalWidth / $originalHeight * $height;
        }

        if (!$height) {
            $height = $originalHeight / $originalWidth * $width;
        }

        return [(int) round($width), (int) round($height)];
    }

    /**
     * Return a given src with with a new extension.
     */
    private function addExtension(string $srcsets, string $extension): string
    {
        $newSrcsets = [];
        foreach (explode(',', $srcsets) as $srcset) {
            $srcset = trim($srcset);
            // when a specific format is given e.g.: "50x50.inverted.jpg" we trim away it here:
            $srcset = $this->removeExtensionFromFormat($srcset);
            $srcParts = explode(' ', $srcset, 2);
            $srcParts[0] .= '.' . $extension;

            $newSrcsets[] = implode(' ', $srcParts);
        }

        return implode(', ', $newSrcsets);
    }

    /**
     * Will return the sulu image format without the extension if given.
     */
    private function removeExtensionFromFormat(string $format): string
    {
        return str_replace(['.svg', '.jpg', '.gif', '.png', '.webp'], '', $format);
    }

    /**
     * @param mixed $media
     */
    private function getMimeType($media): string
    {
        $propertyAccessor = $this->getPropertyAccessor();

        if ($propertyAccessor->isReadable($media, 'mimeType')) {
            return $propertyAccessor->getValue($media, 'mimeType');
        }

        return 'image/jpeg';
    }

    private function getPropertyAccessor(): PropertyAccessor
    {
        // lazy initializing of the property accessor
        if (null === $this->propertyAccessor) {
            $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
