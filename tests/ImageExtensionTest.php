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

namespace Sulu\Twig\Extensions\Tests;

use PHPUnit\Framework\TestCase;
use Sulu\Twig\Extensions\ImageExtension;

class ImageExtensionTest extends TestCase
{
    /**
     * @var ImageExtension
     */
    private $imageExtension;

    /**
     * @var mixed[]
     */
    private $image;

    public function setUp(): void
    {
        $this->imageExtension = new ImageExtension('/lazy');
        $this->image = [
            'title' => 'Title',
            'description' => 'Description',
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
    }

    public function testImageTag(): void
    {
        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $this->imageExtension->getImage($this->image, 'sulu-100x100')
        );
    }

    public function testImageTagObject(): void
    {
        $this->assertSame(
            '<img alt="Title" title="Description" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $this->imageExtension->getImage((object) $this->image, 'sulu-100x100')
        );
    }

    public function testComplexImageTag(): void
    {
        $this->assertSame(
            '<img alt="Logo"' .
            ' title="Description"' .
            ' src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"' .
            ' srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"' .
            ' sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"' .
            ' id="image-id"' .
            ' class="image-class">',
            $this->imageExtension->getImage(
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
            $this->imageExtension->getImage(
                $this->image,
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
            $this->imageExtension->getImage(
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
        $this->assertSame(
            '<img alt="Title" title="Description" src="/lazy/sulu-100x100.svg" data-src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0" class="lazyload">',
            $this->imageExtension->getLazyImage($this->image, 'sulu-100x100')
        );
    }

    public function testLazyComplexImageTag(): void
    {
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
            $this->imageExtension->getLazyImage(
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

    public function testLazyPictureTag(): void
    {
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
            $this->imageExtension->getLazyImage(
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
            $this->imageExtension->getLazyImage(
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
        $this->assertFalse($this->imageExtension->hasLazyImage());
        $this->imageExtension->getImage($this->image, 'sulu-400x400');
        $this->assertFalse($this->imageExtension->hasLazyImage());
        $this->imageExtension->getLazyImage($this->image, 'sulu-400x400');
        $this->assertTrue($this->imageExtension->hasLazyImage());
        $this->imageExtension->getLazyImage($this->image, 'sulu-400x400');
        $this->imageExtension->getImage($this->image, 'sulu-400x400');
        $this->assertTrue($this->imageExtension->hasLazyImage());
    }

    public function testDefaultAttributes(): void
    {
        $imageExtension = new ImageExtension(null, ['loading' => 'lazy']);

        $this->assertSame(
            '<img alt="Title" title="Description" loading="lazy" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">',
            $imageExtension->getImage($this->image, 'sulu-100x100')
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
}
