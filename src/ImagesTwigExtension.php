<?php

namespace Massive\Component\Web;

/**
 * This Twig Extension manages the JavaScript components.
 */
class ImagesTwigExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_fixed_image', [$this, 'getFixedImage'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('get_responsive_image', [$this, 'getResponsiveImage'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('get_responsive_picture', [$this, 'getResponsivePicture'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get a normal image html.
     *
     * Example in TWIG:
     * {% set options = {
     *     retinaSizes: {                   --> Set the retina sizes (srcset) for image.
     *         'sulu-400x400': '4x',        --> Make blank one for image without retina sources.
     *         'sulu-170x170': '2x',
     *         'sulu-100x100': '1x'
     *     },
     *     alt: 'Logo',                     --> Set alt name for image tag (type: string).
     *     id: 'image-id',                  --> Set id for image tag (type: string).
     *     classes: 'image-class',          --> Set classes for image tag (type: string).
     * } %}
     *
     * @param object $image - Contains the image object from sulu.
     * @param string $width - Contains the image format ('500x400').
     * @param array $options - Includes the attributes for the image element (id, classes, retinaSizes, alt).
     *
     * @return string
     */
    public function getFixedImage($image, $width = '', $options = []) : string
    {
        // Return an empty string if no one of the needed parameters is set.
        if (empty($image) || empty($width)) {
            return '';
        }

        // Create the image tag.
        $imageHtml = '<img';

        // Add an id to the image if it was set in options.
        if (array_key_exists('id', $options) && !empty($options['id'])) {
            $imageHtml .= ' id="' . $options['id'] . '"';
        }

        // Add classes to the image if it was set in options.
        if (array_key_exists('classes', $options) && !empty($options['classes'])) {
            $imageHtml .= ' class="' . $options['classes'] . '"';
        }

        // Add the image source as a thumbnail to the image.
        $imageHtml .= ' src="' . $image->getThumbnails()[$width] . '"';

        // Add the srcset to the image if it was set in the options.
        if (array_key_exists('retinaSizes', $options) && !empty($options['retinaSizes'])) {
            $srcSet = [];
            foreach ($options['retinaSizes'] as $key => $value) {
                $srcSet[$key] = $image->getThumbnails()[$key] . ' ' . $value;
            }

            $imageHtml .= ' srcset="' . implode(', ', $srcSet) . '"';
        }

        // Check if the alt attribute is set in the options, else take the default one.
        $imageTitle = $image->getTitle();
        if (array_key_exists('alt', $options) && !empty($options['alt'])) {
            $imageTitle = $options['alt'];
        }

        // Add the alt attribute to the image.
        $imageHtml .= ' alt="' . $imageTitle . '"';

        // Close the image tag.
        $imageHtml .= '/>';

        return $imageHtml;
    }

    /**
     * Get a responsive image html.
     *
     * Example in TWIG:
     * {% set options2 = {
     *     fallBackImageFormat: 'sulu-400x400',     --> Set the fallback format for image if srcset doesn't exist (type: string).
     *     srcsetWidths: {                          --> Set the image format and the width for this image (type: array with string).
     *         'sulu-400x400': '1024w',
     *         'sulu-170x170': '800w',
     *         'sulu-100x100': '460w'
     *     },
     *     sizes: [                                 --> Set responsive image widths for sizes attribute (type: array with string).
     *         '(max-width: 1024px) 100vw',
     *         '(max-width: 800px) 100vw',
     *         '100vw'
     *     ],
     *     alt: 'Logo',                             --> Set alt name for image tag (type: string).
     *     id: 'image-id',                          --> Set id for image tag (type: string).
     *     classes: 'image-class',                  --> Set classes for image tag (type: string).
     * } %}
     *
     * @param object $image
     * @param array $options
     *
     * @return string
     */
    public function getResponsiveImage($image, $options = [])
    {
        // Return an empty string if no one of the needed parameters is set.
        if (empty($image)) {
            return '';
        }

        // Create the image tag.
        $imageHtml = '<img';

        // Add an id to the image if it was set in options.
        if (array_key_exists('id', $options) && !empty($options['id'])) {
            $imageHtml .= ' id="' . $options['id'] . '"';
        }

        // Add classes to the image if it was set in options.
        if (array_key_exists('classes', $options) && !empty($options['classes'])) {
            $imageHtml .= ' class="' . $options['classes'] . '"';
        }

        // Add the image source as a thumbnail to the image.
        if (array_key_exists('fallBackImageFormat', $options) && !empty($options['fallBackImageFormat'])) {
            $imageHtml .= ' src="' . $image->getThumbnails()[$options['fallBackImageFormat']] . '"';
        }

        // Add the sizes to the image if it was set in the options.
        if (array_key_exists('sizes', $options) && !empty($options['sizes'])) {
            $imageHtml .= ' sizes="' . implode(', ', $options['sizes']) . '"';
        }

        // Add the srcset to the image if it was set in the options.
        if (array_key_exists('srcsetWidths', $options) && !empty($options['srcsetWidths'])) {
            $srcSet = [];
            foreach ($options['srcsetWidths'] as $key => $value) {
                $srcSet[$key] = $image->getThumbnails()[$key] . ' ' . $value;
            }

            $imageHtml .= ' srcset="' . implode(', ', $srcSet) . '"';
        }

        // Check if the alt attribute is set in the options, else take the default one.
        $imageTitle = $image->getTitle();
        if (array_key_exists('alt', $options) && !empty($options['alt'])) {
            $imageTitle = $options['alt'];
        }

        // Add the alt attribute to the image.
        $imageHtml .= ' alt="' . $imageTitle . '"';

        // Close the image tag.
        $imageHtml .= '/>';

        return $imageHtml;
    }

    public function getResponsivePicture($image, $options)
    {

    }
}
