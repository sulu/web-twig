<?php

namespace Massive\Component\Web;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * This Twig Extension manages the image formats.
 */
class ImageTwigExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_image', [$this, 'getImage'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get an image or picture tag with given attributes.
     *
     * @param mixed $media
     * @param array|string $attributes
     * @param array $sources
     *
     * @return string
     */
    public function getImage($media, $attributes = [], $sources = [])
    {
        // Return an empty string if no one of the needed parameters is set.
        if (empty($media) || empty($attributes)) {
            return '';
        }

        if (is_array($media)) {
            $media = (object) $media;
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        // Thumbnails exists all times - it only can be empty.
        $thumbnails = $propertyAccessor->getValue($media, 'thumbnails');

        // If attributes is an string, convert it to an array (like '{{ get_image_tag(media, '650x') }}').
        if (is_string($attributes)) {
            $attributes = [
                'src' => $attributes,
            ];
        }

        // Get title from object to use as alt attribute.
        $alt = $propertyAccessor->getValue($media, 'title');

        // Get description from object to use as title attribute else fallback to alt attribute.
        $title = $propertyAccessor->getValue($media, 'description') ?: $alt;

        // Get the image tag with all given attributes.
        $imgTag = $this->createTag('img', array_merge(['alt' => $alt, 'title' => $title], $attributes), $thumbnails);

        if (empty($sources)) {
            return $imgTag;
        }

        $sourceTags = '';
        foreach ($sources as $media => $sourceAttributes) {
            // Get the source tag with all given attributes.
            $sourceTags .= $this->createTag('source', array_merge(['media' => $media], $sourceAttributes), $thumbnails);
        }

        // Returns the picture tag with all sources and the fallback image tag.
        return sprintf('<picture>%s%s</picture>', $sourceTags, $imgTag);
    }

    /**
     * Create html tag.
     *
     * @param string $tag
     * @param array $attributes
     * @param array $thumbnails
     *
     * @return string
     */
    private function createTag($tag, $attributes, $thumbnails)
    {
        $output = '';

        foreach ($attributes as $key => $value) {
            if ('src' === $key) {
                // Set the thumbnail instead of image itself.
                $value = $thumbnails[$value];
            } elseif ('srcset' === $key) {
                // Replace thumbnail format in srcset.
                $value = $this->srcsetThumbnailReplace($value, $thumbnails);
            }

            $output .= sprintf(' %s="%s"', $key, htmlentities($value));
        }

        return sprintf('<%s%s>', $tag, $output);
    }

    /**
     * Replace the given image format with an thumbnail.
     *
     * @param string $value
     * @param array $thumbnails
     *
     * @return string
     */
    private function srcsetThumbnailReplace($value, $thumbnails)
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
}
