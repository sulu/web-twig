<?php

namespace Massive\Component\Web;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * This Twig Extension manages the images formats.
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
     * @param mixed $image - Contains the image object from sulu.
     * @param string $width - Contains the image format ('500x400').
     * @param array $options - Includes the attributes for the image element (id, classes, retinaSizes, alt).
     *
     * @return string
     */
    public function getFixedImage($image, string $width, $options = array())
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        // Return an empty string if no one of the needed parameters is set.
        if (empty($width) || empty($image) || empty($propertyAccessor->getValue($image, 'thumbnails')[$width])) {
            return '';
        }

        // Set thumbnails.
        $thumbnails = $propertyAccessor->getValue($image, 'thumbnails');

        // Open the image tag.
        $imageHtml = '<img';

        // Add an id to the image if it was set in options.
        $imageHtml .= $this->getIdHtml($options);

        // Add classes to the image if it was set in options.
        $imageHtml .= $this->getClassHtml($options);

        // Add the image source as a thumbnail to the image.
        $imageHtml .= ' src="' . $thumbnails[$width] . '"';

        // Add the srcset to the image if it was set in the options.
        if (array_key_exists('retinaSizes', $options) && !empty($options['retinaSizes'])) {
            $srcSet = array();
            foreach ($options['retinaSizes'] as $key => $value) {
                $srcSet[] = $thumbnails[$key] . ' ' . $value;
            }

            $imageHtml .= ' srcset="' . implode(', ', $srcSet) . '"';
        }

        // Add the alt attribute to the image.
        $imageHtml .= $this->getAltHtml($propertyAccessor->getValue($image, 'title'), $options);

        // Close the image tag.
        $imageHtml .= '/>';

        return $imageHtml;
    }

    /**
     * Get a responsive image html.
     *
     * Example in TWIG:
     * {% set options = {
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
     * @param mixed $image
     * @param array $options
     *
     * @return string
     */
    public function getResponsiveImage($image, array $options)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        // Return an empty string if no one of the needed parameters is set.
        if (empty($image) || empty($propertyAccessor->getValue($image, 'thumbnails')) || empty($options)) {
            return '';
        }

        // Thumbnails exists all times - it only can be empty.
        $thumbnails = $propertyAccessor->getValue($image, 'thumbnails');

        // Open the image tag.
        $imageHtml = '<img';

        // Add an id to the image if it was set in options.
        $imageHtml .= $this->getIdHtml($options);

        // Add classes to the image if it was set in options.
        $imageHtml .= $this->getClassHtml($options);

        // Add the image source as a thumbnail to the image.
        if (array_key_exists('fallBackImageFormat', $options) && !empty($options['fallBackImageFormat'])) {
            $imageHtml .= ' src="' . $thumbnails[$options['fallBackImageFormat']] . '"';
        }

        // Add the sizes to the image if it was set in the options.
        if (array_key_exists('sizes', $options) && !empty($options['sizes'])) {
            $imageHtml .= ' sizes="' . implode(', ', $options['sizes']) . '"';
        }

        // Add the srcset to the image if it was set in the options.
        if (array_key_exists('srcsetWidths', $options) && !empty($options['srcsetWidths'])) {
            $srcSet = array();
            foreach ($options['srcsetWidths'] as $key => $value) {
                $srcSet[$key] = $thumbnails[$key] . ' ' . $value;
            }

            $imageHtml .= ' srcset="' . implode(', ', $srcSet) . '"';
        }

        // Add the alt attribute to the image.
        $imageHtml .= $this->getAltHtml($propertyAccessor->getValue($image, 'title'), $options);

        // Close the image tag.
        $imageHtml .= '/>';

        return $imageHtml;
    }

    /**
     * Get a responsive picture html.
     *
     * Example in TWIG:
     * {% set options = {
     *     fallBackImageFormat: 'sulu-400x400',
     *     imageFormats: [
     *         'sulu-400x400',
     *         'sulu-170x170',
     *         'sulu-100x100'
     *     ],
     *     medias: [
     *         '(max-width: 1024px)',
     *         '(max-width: 800px)'
     *     ],
     *     retinaSizes: {
     *         'sulu-400x400': '3x',
     *         'sulu-170x170': '2x',
     *         'sulu-100x100': '1x'
     *     },
     *     alt: 'Logo',
     *     id: 'image-id',
     *     classes: 'image-class',
     * } %}
     *
     * @param mixed $image
     * @param array $options
     *
     * @return string
     */
    public function getResponsivePicture($image, array $options)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        // Return an empty string if no one of the needed parameters is set.
        if (empty($image) || empty($propertyAccessor->getValue($image, 'thumbnails')) || empty($options)) {
            return '';
        }

        // Thumbnails exists all times - it only can be empty.
        $thumbnails = $propertyAccessor->getValue($image, 'thumbnails');

        // Create the picture tag.
        $pictureHtml = '<picture>';

        // Generate the srcset for source attribute and add the source attribute to picture tag.
        if (array_key_exists('medias', $options) && !empty($options['medias'])
            && array_key_exists('retinaSizes', $options) && !empty($options['retinaSizes'])) {
            foreach ($options['medias'] as $key => $value) {
                $srcSet = array();

                // Set a fallback image format if no retina size can be used.
                $srcSet[] = $thumbnails[$options['imageFormats'][$key]];

                // Add each retina sizes to the srcSet array.
                foreach ($options['retinaSizes'] as $retinaKey => $retinaValue) {
                    $srcSet[] = $thumbnails[$retinaKey] . ' ' . $retinaValue;
                }

                // Add source attribute to picture tag.
                $pictureHtml .= '<source media="' . $value . '" srcset="' . implode(', ', $srcSet) . '">';
            }
        }

        // Open the image tag.
        $pictureHtml .= '<img';

        // Add an id to the image if it was set in options.
        $pictureHtml .= $this->getIdHtml($options);

        // Add classes to the image if it was set in options.
        $pictureHtml .= $this->getClassHtml($options);

        // Add the image source as a thumbnail to the image.
        if (array_key_exists('fallBackImageFormat', $options) && !empty($options['fallBackImageFormat'])) {
            $pictureHtml .= ' src="' . $thumbnails[$options['fallBackImageFormat']] . '"';
        }

        // Add the alt attribute to the image.
        $pictureHtml .= $this->getAltHtml($propertyAccessor->getValue($image, 'title'), $options);

        // Close the image tag.
        $pictureHtml .= '/>';

        // Close the picture tag.
        $pictureHtml .= '</picture>';

        return $pictureHtml;
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getIdHtml($options)
    {
        if (array_key_exists('id', $options) && !empty($options['id'])) {
            return ' id="' . $options['id'] . '"';
        }

        return '';
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getClassHtml($options)
    {
        if (array_key_exists('classes', $options) && !empty($options['classes'])) {
            return ' class="' . $options['classes'] . '"';
        }

        return '';
    }

    /**
     * @param string $imageTitle
     * @param array $options
     *
     * @return string
     */
    protected function getAltHtml($imageTitle, $options)
    {
        // Check if the alt attribute is set in the options, else take the default one.
        $title = $imageTitle;
        if (array_key_exists('alt', $options) && !empty($options['alt'])) {
            $title = $options['alt'];
        }

        return ' alt="' . $title . '"';
    }
}
