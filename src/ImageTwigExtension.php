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
     * @param array $attributes
     * @param array $sources
     *
     * @return string
     */
    public function getImage($media, $attributes = [], $sources = [])
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        // If attributes is an string, convert it to an array (like '{{ get_image_tag(media, '650x') }}').
        if (is_string($attributes)) {
            $attributes = [
                'src' => $attributes,
            ];
        }

        // Return an empty string if no one of the needed parameters is set.
        if (empty($media)
            || empty($attributes)
            || empty($propertyAccessor->getValue($media, 'thumbnails'))) {
            return '';
        }

        // Thumbnails exists all times - it only can be empty.
        $thumbnails = $propertyAccessor->getValue($media, 'thumbnails');


        // Open the image or picture tag.
        $imageHtml = '<img';
        if (!empty($sources)) {
            $imageHtml = '<picture>';

            // Generate the srcset for source attribute and add the source attribute to picture tag.
            if (array_key_exists('sourceMedias', $sources) && !empty($sources['sourceMedias'])
                && array_key_exists('sourceSrcset', $sources) && !empty($sources['sourceSrcset'])) {
                foreach ($sources['sourceMedias'] as $key => $value) {
                    $srcSet = [];

                    // Set a fallback image format if no retina size can be used.
                    $srcSet[] = $thumbnails[array_keys($sources['sourceSrcset'])[$key]];

                    // Add each retina sizes to the srcSet array.
                    foreach ($sources['sourceSrcset'] as $retinaKey => $retinaValue) {
                        $srcSet[] = $thumbnails[$retinaKey] . ' ' . $retinaValue;
                    }

                    // Add source attribute to picture tag.
                    $imageHtml .= '<source media="' . $value . '" srcset="' . implode(', ', $srcSet) . '">';
                }
            }

            // Open the image tag inside the picture tag.
            $imageHtml .= '<img';
        }

        // Add an id to the image if it was set in options.
        if (array_key_exists('id', $attributes) && !empty($attributes['id'])) {
            $imageHtml .= ' id="' . $attributes['id'] . '"';
        }

        // Add classes to the image if it was set in options.
        if (array_key_exists('classes', $attributes) && !empty($attributes['classes'])) {
            $imageHtml .= ' class="' . $attributes['classes'] . '"';
        }

        // Add the image source as a thumbnail to the image.
        if (array_key_exists('src', $attributes) && !empty($attributes['src'])) {
            $imageHtml .= ' src="' . $thumbnails[$attributes['src']] . '"';
        }

        // Add the sizes to the image if it was set in the options.
        if (array_key_exists('sizes', $attributes) && !empty($attributes['sizes'])) {
            $imageHtml .= ' sizes="' . $attributes['sizes'] . '"';
        }

        // Add the srcset to the image if it was set in the options.
        // The srcset needed to be as an array to render the thumbnail from image.
        if (array_key_exists('srcset', $attributes) && !empty($attributes['srcset'])) {
            $srcSet = [];
            foreach ($attributes['srcset'] as $key => $value) {
                $srcSet[$key] = $thumbnails[$key] . ' ' . $value;
            }

            $imageHtml .= ' srcset="' . implode(', ', $srcSet) . '"';
        }

        // Check if the alt attribute is set in the options, else take the default one.
        $title = $propertyAccessor->getValue($media, 'title');
        if (array_key_exists('alt', $attributes) && !empty($attributes['alt'])) {
            $title = $attributes['alt'];
        }

        // Add the alt attribute to the image.
        $imageHtml .= ' alt="' . $title . '"';

        // Close the image tag.
        $imageHtml .= '/>';

        // Close the picture tag, if it was defined.
        if (!empty($sources)) {
            $imageHtml .= '</pictures>';
        }

        return $imageHtml;
    }
}
