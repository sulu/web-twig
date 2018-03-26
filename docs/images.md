# Web Images Twig Extension

The web images twig extension gives you a simple and efficient way to handle your image over twig.

## Setup

### Service Registration

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

**xml**

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services
        http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="app.web_images" class="Massive\Component\Web\ImagesTwigExtension">
            <tag name="twig.extension" />
        </service>
    </services>
</container>
```

**yml**

```yml
services:
    app.web_images:
        class: Massive\Component\Web\ImagesTwigExtension
        tags:
            - { name: twig.extension }
```

## Usage

#### Fixed image

To get a image without responsive states, use this.

```twig
{% set options = {
    retinaSizes: {
        'sulu-400x400': '4x',
        'sulu-170x170': '2x',
        'sulu-100x100': '1x'
    },
    alt: 'Logo',
    id: 'image-id',
    classes: 'image-class',
} %}

{{ get_fixed_image(image, '460x590', options) }}
```

#### Responsive image

To get an image with responsive states, use this.

```twig
{% set options = {
    fallBackImageFormat: 'sulu-400x400',
    srcsetWidths: {
        'sulu-400x400': '1024w',
        'sulu-170x170': '800w',
        'sulu-100x100': '460w'
    },
    sizes: [
        '(max-width: 1024px) 100vw',
        '(max-width: 800px) 100vw',
        '100vw'
    ],
    alt: 'Logo',
    id: 'image-id',
    classes: 'image-class',
} %}

{{ get_responsive_image(image, options) }}
```

#### Responsive picture

To get an picture with responsive states, use this.

```twig
{% set options = {
    fallBackImageFormat: 'sulu-400x400',
    imageFormats: ['sulu-400x400', 'sulu-170x170', 'sulu-100x100'],
    medias: [
        '(max-width: 1024px)',
        '(max-width: 800px)'
    ],
    retinaSizes: {
        'sulu-400x400': '3x',
        'sulu-170x170': '2x',
        'sulu-100x100': '1x'
    },
    alt: 'Logo',
    id: 'image-id',
    classes: 'image-class',
} %}

{{ get_responsive_picture(image, options) }}
```
