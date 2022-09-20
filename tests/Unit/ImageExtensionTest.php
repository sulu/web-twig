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

namespace Sulu\Twig\Extensions\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sulu\Twig\Extensions\ImageExtension;

class ImageExtensionTest extends TestCase
{
    /**
     * @var mixed[]
     */
    private $image;

    /**
     * @var mixed[]
     */
    private $minimalImage;

    /**
     * @var mixed[]
     */
    private $svgImage;

    protected function setUp(): void
    {
        $this->image = [
            'title' => 'Title',
            'description' => 'Description',
            'mimeType' => 'image/jpeg',
            'thumbnails' => [
                'sulu-100x100' => '/uploads/media/sulu-100x100/01/image.jpg?v=1-0',
                'sulu-100x100.webp' => '/uploads/media/sulu-100x100/01/image.webp?v=1-0',
                'sulu-100x100@2x' => '/uploads/media/sulu-100x100@2x/01/image.jpg?v=1-0',
                'sulu-100x100@2x.webp' => '/uploads/media/sulu-100x100@2x/01/image.webp?v=1-0',
                'sulu-170x170' => '/uploads/media/sulu-170x170/01/image.jpg?v=1-0',
                'sulu-170x170.webp' => '/uploads/media/sulu-170x170/01/image.webp?v=1-0',
                'sulu-400x400' => '/uploads/media/sulu-400x400/01/image.jpg?v=1-0',
                'sulu-400x400.webp' => '/uploads/media/sulu-400x400/01/image.webp?v=1-0',
                'sulu-260x' => '/uploads/media/sulu-400x400/01/image.jpg?v=1-0',
                'sulu-260x.webp' => '/uploads/media/sulu-400x400/01/image.webp?v=1-0',
            ],
            'properties' => [
                'width' => 1920,
                'height' => 1080,
            ],
        ];

        $this->minimalImage = [
            'thumbnails' => [
                'sulu-100x100' => '/uploads/media/sulu-100x100/01/image.jpg?v=1-0',
                'sulu-100x100.webp' => '/uploads/media/sulu-100x100/01/image.webp?v=1-0',
                'sulu-100x100@2x' => '/uploads/media/sulu-100x100@2x/01/image.jpg?v=1-0',
                'sulu-100x100@2x.webp' => '/uploads/media/sulu-100x100@2x/01/image.webp?v=1-0',
                'sulu-170x170' => '/uploads/media/sulu-170x170/01/image.jpg?v=1-0',
                'sulu-170x170.webp' => '/uploads/media/sulu-170x170/01/image.webp?v=1-0',
                'sulu-400x400' => '/uploads/media/sulu-400x400/01/image.jpg?v=1-0',
                'sulu-400x400.webp' => '/uploads/media/sulu-400x400/01/image.webp?v=1-0',
            ],
        ];

        $this->svgImage = [
            'title' => 'Title',
            'description' => 'Description',
            'mimeType' => 'image/svg',
            'thumbnails' => [
                'sulu-100x100' => '/uploads/media/sulu-100x100/01/image.svg?v=1-0',
                'sulu-100x100.webp' => '/uploads/media/sulu-100x100/01/image.webp?v=1-0',
                'sulu-100x100@2x' => '/uploads/media/sulu-100x100@2x/01/image.svg?v=1-0',
                'sulu-100x100@2x.webp' => '/uploads/media/sulu-100x100@2x/01/image.webp?v=1-0',
                'sulu-170x170' => '/uploads/media/sulu-170x170/01/image.svg?v=1-0',
                'sulu-170x170.webp' => '/uploads/media/sulu-170x170/01/image.webp?v=1-0',
                'sulu-400x400' => '/uploads/media/sulu-400x400/01/image.svg?v=1-0',
                'sulu-400x400.webp' => '/uploads/media/sulu-400x400/01/image.webp?v=1-0',
            ],
            'properties' => [
                'width' => 1920,
                'height' => 1080,
            ],
        ];
    }

    public function testImageTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $imageExtension->getImage($this->image, 'sulu-100x100')
        );
    }

    public function testImageTagObject(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $imageExtension->getImage((object) $this->image, 'sulu-100x100')
        );
    }

    public function testComplexImageTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<img alt="Logo"' .
            ' title="Description"' .
            ' src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
            ' srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
            ' id="image-id"' .
            ' class="image-class">',
            $imageExtension->getImage(
                $this->image,
                [
                    'src' => 'sulu-400x400',
                    'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                    'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    'id' => 'image-id',
                    'class' => 'image-class',
                    'alt' => 'Logo',
                ]
            )
        );
    }

    public function testPictureTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<picture>' .
            '<source media="(max-width: 1024px)"' .
                ' srcset="/uploads/media/sulu-170x170/01/image.jpg?v=1-0">' .
            '<source media="(max-width: 650px)"' .
                ' srcset="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">' .
            '<img alt="Title"' .
                ' title="Description"' .
                ' src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0">' .
            '</picture>',
            $imageExtension->getImage(
                $this->image,
                'sulu-400x400',
                [
                    '(max-width: 1024px)' => 'sulu-170x170',
                    '(max-width: 650px)' => 'sulu-100x100',
                ]
            )
        );
    }

    public function testComplexWebpPictureTag(): void
    {
        $imageExtension = new ImageExtension(null, [], ['webp' => 'image/webp']);

        $this->assertSame(
            '<picture>' .
            '<source srcset="/uploads/media/sulu-100x100/01/image.webp?v=1-0 460w, /uploads/media/sulu-170x170/01/image.webp?v=1-0 800w, /uploads/media/sulu-400x400/01/image.webp?v=1-0 1024w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
            ' type="image/webp">' .
            '<img alt="Logo"' .
            ' title="Description"' .
            ' src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
            ' srcset="/uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
            ' id="image-id"' .
            ' class="image-class">' .
            '</picture>',
            $imageExtension->getImage(
                $this->image,
                [
                    'src' => 'sulu-400x400',
                    'srcset' => 'sulu-100x100 460w, sulu-170x170 800w, sulu-400x400 1024w',
                    'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    'id' => 'image-id',
                    'class' => 'image-class',
                    'alt' => 'Logo',
                ]
            )
        );
    }

    public function testComplexWebpPictureTagRetina(): void
    {
        $imageExtension = new ImageExtension(null, [], ['webp' => 'image/webp']);

        $this->assertSame(
            '<picture>' .
            '<source srcset="/uploads/media/sulu-100x100/01/image.webp?v=1-0 1x, /uploads/media/sulu-400x400/01/image.webp?v=1-0 2x"' .
            ' type="image/webp">' .
            '<img alt="Logo"' .
            ' title="Description"' .
            ' src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0"' .
            ' srcset="/uploads/media/sulu-100x100/01/image.jpg?v=1-0 1x, /uploads/media/sulu-400x400/01/image.jpg?v=1-0 2x"' .
            ' id="image-id"' .
            ' class="image-class">' .
            '</picture>',
            $imageExtension->getImage(
                $this->image,
                [
                    'src' => 'sulu-100x100',
                    'srcset' => 'sulu-100x100 1x, sulu-400x400 2x',
                    'id' => 'image-id',
                    'class' => 'image-class',
                    'alt' => 'Logo',
                ]
            )
        );
    }

    public function testPictureTagMinimalImage(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<picture>' .
            '<source media="(max-width: 1024px)"' .
            ' srcset="/uploads/media/sulu-170x170/01/image.jpg?v=1-0">' .
            '<source media="(max-width: 650px)"' .
            ' srcset="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">' .
            '<img alt="image"' .
            ' title="image"' .
            ' src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0">' .
            '</picture>',
            $imageExtension->getImage(
                $this->minimalImage,
                'sulu-400x400',
                [
                    '(max-width: 1024px)' => 'sulu-170x170',
                    '(max-width: 650px)' => 'sulu-100x100',
                ]
            )
        );
    }

    public function testComplexPictureTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<picture>' .
            '<source media="(max-width: 1024px)"' .
                ' srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
                ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">' .
            '<source media="(max-width: 650px)"' .
                ' srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
                ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">' .
            '<img alt="Title"' .
                ' title="Description"' .
                ' src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
                ' class="image-class">' .
            '</picture>',
            $imageExtension->getImage(
                $this->image,
                [
                    'src' => 'sulu-400x400',
                    'class' => 'image-class',
                ],
                [
                    '(max-width: 1024px)' => [
                        'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                        'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    ],
                    '(max-width: 650px)' => [
                        'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                        'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    ],
                ]
            )
        );
    }

    public function testLazyImageTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<img alt="Title" title="Description" src="/lazy/sulu-100x100.svg" data-src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0" class="lazyload">',
            $imageExtension->getLazyImage($this->image, 'sulu-100x100')
        );
    }

    public function testLazyComplexImageTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<img alt="Logo"' .
            ' title="Description"' .
            ' src="/lazy/sulu-400x400.svg"' .
            ' data-src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
            ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
            ' data-srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
            ' id="image-id"' .
            ' class="image-class lazyload">',
            $imageExtension->getLazyImage(
                $this->image,
                [
                    'src' => 'sulu-400x400',
                    'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                    'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    'id' => 'image-id',
                    'class' => 'image-class',
                    'alt' => 'Logo',
                ]
            )
        );
    }

    public function testLazyComplexImageTagMinimalImage(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<img alt="image"' .
            ' title="image"' .
            ' src="/lazy/sulu-400x400.svg"' .
            ' data-src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
            ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
            ' data-srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
            ' id="image-id"' .
            ' class="image-class lazyload">',
            $imageExtension->getLazyImage(
                $this->minimalImage,
                [
                    'src' => 'sulu-400x400',
                    'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                    'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    'id' => 'image-id',
                    'class' => 'image-class',
                ]
            )
        );
    }

    public function testLazyPictureTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<picture>' .
            '<source media="(max-width: 1024px)"' .
            ' srcset="/lazy/sulu-170x170.svg"' .
            ' data-srcset="/uploads/media/sulu-170x170/01/image.jpg?v=1-0">' .
            '<source media="(max-width: 650px)"' .
            ' srcset="/lazy/sulu-100x100.svg"' .
            ' data-srcset="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">' .
            '<img alt="Title"' .
            ' title="Description"' .
            ' src="/lazy/sulu-400x400.svg"' .
            ' data-src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
            ' class="lazyload">' .
            '</picture>',
            $imageExtension->getLazyImage(
                $this->image,
                'sulu-400x400',
                [
                    '(max-width: 1024px)' => 'sulu-170x170',
                    '(max-width: 650px)' => 'sulu-100x100',
                ]
            )
        );
    }

    public function testLazyComplexPictureTag(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertSame(
            '<picture>' .
            '<source media="(max-width: 1024px)"' .
            ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
            ' data-srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">' .
            '<source media="(max-width: 650px)"' .
            ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
            ' data-srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">' .
            '<img alt="Title"' .
            ' title="Description"' .
            ' src="/lazy/sulu-400x400.svg"' .
            ' data-src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
            ' class="image-class lazyload">' .
            '</picture>',
            $imageExtension->getLazyImage(
                $this->image,
                [
                    'src' => 'sulu-400x400',
                    'class' => 'image-class',
                ],
                [
                    '(max-width: 1024px)' => [
                        'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                        'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    ],
                    '(max-width: 650px)' => [
                        'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                        'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    ],
                ]
            )
        );
    }

    public function testHasLazyImage(): void
    {
        $imageExtension = new ImageExtension('/lazy');

        $this->assertFalse($imageExtension->hasLazyImage());
        $imageExtension->getImage($this->image, 'sulu-400x400');
        $this->assertFalse($imageExtension->hasLazyImage());
        $imageExtension->getLazyImage($this->image, 'sulu-400x400');
        $this->assertTrue($imageExtension->hasLazyImage());
        $imageExtension->getLazyImage($this->image, 'sulu-400x400');
        $imageExtension->getImage($this->image, 'sulu-400x400');
        $this->assertTrue($imageExtension->hasLazyImage());
    }

    public function testDefaultAttributes(): void
    {
        $imageExtension = new ImageExtension(null, ['loading' => 'lazy']);

        $this->assertSame(
            '<img alt="Title" title="Description" loading="lazy" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $imageExtension->getImage($this->image, 'sulu-100x100')
        );
    }

    public function testRemoveDefaultAttributes(): void
    {
        $imageExtension = new ImageExtension(null, ['loading' => 'lazy']);

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $imageExtension->getImage(
                $this->image,
                [
                    'src' => 'sulu-100x100',
                    'loading' => null,
                ]
            )
        );
    }

    public function testReplaceLoadingAuto(): void
    {
        $imageExtension = new ImageExtension(null, ['loading' => 'lazy']);

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $imageExtension->getImage(
                $this->image,
                [
                    'src' => 'sulu-100x100',
                    'loading' => 'auto',
                ]
            )
        );
    }

    public function testDefaultAttributesUnset(): void
    {
        $imageExtension = new ImageExtension(null, ['loading' => 'lazy']);

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $imageExtension->getImage($this->image, [
                'src' => 'sulu-100x100',
                'loading' => null,
            ])
        );
    }

    public function testDefaultAdditionalTypes(): void
    {
        $imageExtension = new ImageExtension(null, [], ['webp' => 'image/webp']);

        $this->assertSame(
            '<picture>' .
            '<source srcset="/uploads/media/sulu-100x100/01/image.webp?v=1-0"' .
                ' type="image/webp">' .
            '<img alt="Title"' .
                ' title="Description"' .
                ' src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">' .
            '</picture>',
            $imageExtension->getImage($this->image, [
                'src' => 'sulu-100x100',
            ])
        );
    }

    public function testIgnoreAdditionalTypesForSvg(): void
    {
        $imageExtension = new ImageExtension(null, [], ['webp' => 'image/webp']);

        $this->assertSame(
        '<img alt="Title"' .
            ' title="Description"' .
            ' src="/uploads/media/sulu-100x100/01/image.svg?v=1-0">',
            $imageExtension->getImage($this->svgImage, [
                'src' => 'sulu-100x100',
            ])
        );
    }

    public function testAdditionalTypes(): void
    {
        $imageExtension = new ImageExtension(null, [], []);

        $this->assertSame(
            '<picture>' .
            '<source srcset="/uploads/media/sulu-100x100/01/image.webp?v=1-0"' .
                ' type="image/webp">' .
            '<img alt="Title"' .
                ' title="Description"' .
                ' src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">' .
            '</picture>',
            $imageExtension->getImage($this->image, [
                'src' => 'sulu-100x100',
            ], [], ['webp' => 'image/webp'])
        );
    }

    public function testAdditionalTypesWithSrcSet(): void
    {
        $imageExtension = new ImageExtension(null, [], []);

        $this->assertSame(
            '<picture>' .
            '<source srcset="/uploads/media/sulu-100x100/01/image.webp?v=1-0, /uploads/media/sulu-100x100@2x/01/image.webp?v=1-0 2x"' .
            ' type="image/webp">' .
            '<img alt="Title"' .
            ' title="Description"' .
            ' src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0"' .
            ' srcset="/uploads/media/sulu-100x100@2x/01/image.jpg?v=1-0 2x">' .
            '</picture>',
            $imageExtension->getImage($this->image, [
                'src' => 'sulu-100x100',
                'srcset' => 'sulu-100x100@2x 2x',
            ], [], ['webp' => 'image/webp'])
        );
    }

    public function testAdditionalLazyComplexPictureTag(): void
    {
        $imageExtension = new ImageExtension('/lazy', [], ['webp' => 'image/webp']);

        $this->assertSame(
            '<picture>' .
            '<source media="(max-width: 1024px)"' .
                ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
                ' data-srcset="/uploads/media/sulu-400x400/01/image.webp?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.webp?v=1-0 800w, /uploads/media/sulu-100x100/01/image.webp?v=1-0 460w"' .
                ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
                ' type="image/webp">' .
            '<source media="(max-width: 1024px)"' .
                ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
                ' data-srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
                ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">' .
            '<source media="(max-width: 650px)"' .
                ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
                ' data-srcset="/uploads/media/sulu-400x400/01/image.webp?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.webp?v=1-0 800w, /uploads/media/sulu-100x100/01/image.webp?v=1-0 460w"' .
                ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
                ' type="image/webp">' .
            '<source media="(max-width: 650px)"' .
                ' srcset="/lazy/sulu-400x400.svg 1024w, /lazy/sulu-170x170.svg 800w, /lazy/sulu-100x100.svg 460w"' .
                ' data-srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
                ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">' .
            '<source srcset="/lazy/sulu-400x400.svg"' .
                ' data-srcset="/uploads/media/sulu-400x400/01/image.webp?v=1-0"' .
                ' type="image/webp">' .
            '<img alt="Title"' .
                ' title="Description"' .
                ' src="/lazy/sulu-400x400.svg"' .
                ' data-src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
                ' class="image-class lazyload">' .
            '</picture>',
            $imageExtension->getLazyImage(
                $this->image,
                [
                    'src' => 'sulu-400x400',
                    'class' => 'image-class',
                ],
                [
                    '(max-width: 1024px)' => [
                        'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                        'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    ],
                    '(max-width: 650px)' => [
                        'srcset' => 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
                        'sizes' => '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
                    ],
                ]
            )
        );
    }

    /**
     * @dataProvider aspectRatioDataProvider
     */
    public function testAspectRatio(string $format, string $expectedWidth, string $expectedHeight): void
    {
        $imageExtension = new ImageExtension(null, [], [], true);

        $image = array_replace_recursive(
            $this->image,
            [
                'thumbnails' => [
                    $format => '/uploads/media/' . $format . '/01/image.jpg?v=1-0',
                    $format . '.webp' => '/uploads/media/' . $format . '/01/image.webp?v=1-0',
                ],
                'properties' => [
                    'width' => 1920,
                    'height' => 1080,
                ],
            ]
        );

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/' . $format . '/01/image.jpg?v=1-0" width="' . $expectedWidth . '" height="' . $expectedHeight . '">',
            $imageExtension->getImage($image, $format)
        );
    }

    public function testAspectRatioNoProperties(): void
    {
        $imageExtension = new ImageExtension(null, [], [], true);

        $image = array_replace_recursive(
            $this->image,
            [
                'thumbnails' => [
                    '200x100-inset' => '/uploads/media/200x100-inset/01/image.jpg?v=1-0',
                    '200x100-inset' . '.webp' => '/uploads/media/200x100-inset/01/image.webp?v=1-0',
                ],
            ]
        );

        unset($image['properties']);

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/200x100-inset/01/image.jpg?v=1-0">',
            $imageExtension->getImage($image, '200x100-inset')
        );
    }

    /**
     * @dataProvider aspectRatioDataProvider
     */
    public function testAspectRatioWithConfiguration(string $format, string $expectedWidth, string $expectedHeight): void
    {
        preg_match('/(\d+)?x(\d+)?(-inset)?(@)?(\d)?(x)?/', $format, $matches);

        $scale = !empty($matches[5]) ? (float) $matches[5] : 1;
        $x = !empty($matches[1]) ? (int) $matches[1] : null;
        $y = !empty($matches[2]) ? (int) $matches[2] : null;
        $isInset = !empty($matches[3]);

        $imageExtension = new ImageExtension(null, [], [], true, [
            $format => [
                'scale' => [
                    'x' => $x,
                    'y' => $y,
                    'mode' => $isInset ? 1 : 2,
                    'retina' => 1 !== $scale,
                ],
            ],
        ]);

        $image = array_replace_recursive(
            $this->image,
            [
                'thumbnails' => [
                    $format => '/uploads/media/' . $format . '/01/image.jpg?v=1-0',
                    $format . '.webp' => '/uploads/media/' . $format . '/01/image.webp?v=1-0',
                ],
                'properties' => [
                    'width' => 1920,
                    'height' => 1080,
                ],
            ]
        );

        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/' . $format . '/01/image.jpg?v=1-0" width="' . $expectedWidth . '" height="' . $expectedHeight . '">',
            $imageExtension->getImage($image, $format)
        );
    }

    /**
     * @return \Generator<array{string, string, string}>
     */
    public function aspectRatioDataProvider(): \Generator
    {
        yield ['100x', '100', '56'];
        yield ['x100', '178', '100'];
        yield ['100x100', '100', '100'];
        yield ['200x50', '200', '50'];
        yield ['100x@2x', '200', '113'];
        yield ['x100@2x', '356', '200'];
        yield ['100x100@2x', '200', '200'];
        yield ['200x50@2x', '400', '100'];
        yield ['100x-inset', '100', '56'];
        yield ['x100-inset', '178', '100'];
        yield ['100x100-inset', '100', '56'];
        yield ['200x50-inset', '89', '50'];
        yield ['100x-inset@2x', '200', '113'];
        yield ['x100-inset@2x', '356', '200'];
        yield ['100x100-inset@2x', '200', '113'];
        yield ['sulu-100x', '100', '56'];
        yield ['sulu-x100', '178', '100'];
        yield ['sulu-100x100', '100', '100'];
        yield ['sulu-100x-inset', '100', '56'];
        yield ['sulu-x100-inset', '178', '100'];
        yield ['sulu-100x100-inset', '100', '56'];
        yield ['sulu-100x-inset@2x', '200', '113'];
        yield ['sulu-x100-inset@2x', '356', '200'];
        yield ['sulu-100x100-inset2x', '200', '113'];
    }
}
