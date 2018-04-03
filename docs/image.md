# Web Image Twig Extension

The web image twig extension gives you a simple and efficient way to handle your image over twig.

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
        <service id="app.web_image" class="Massive\Component\Web\ImageTwigExtension">
            <tag name="twig.extension" />
        </service>
    </services>
</container>
```

**yml**

```yml
services:
    app.web_image:
        class: Massive\Component\Web\ImageTwigExtension
        tags:
            - { name: twig.extension }
```

## Usage

#### Get an image

To get an image, use the following code.

```twig
{% set options1 = {
    src: 'sulu-400x400',
    srcset: {
        'sulu-400x400': '1024w',
        'sulu-170x170': '800w',
        'sulu-100x100': '460w'
    },
    sizes: '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
    id: 'image-id',
    classes: 'image-class',
    alt: 'Logo'
} %}

{% set options2 = {
    sourceMedias: [
        '(max-width: 1024px)',
        '(max-width: 800px)'
    ],
    sourceSrcset: {
        'sulu-400x400': '3x',
        'sulu-170x170': '2x',
        'sulu-100x100': '1x'
    }
} %}

{# To get a simple image only with src. #}
{{ get_image(image, 'sulu-100x100') }}

{# To get a responsive image. #}
{{ get_image(image, options1) }}

{# To get a responsive complex picture. #}
{{ get_image(image, options1, options2) }}
```
