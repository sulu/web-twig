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
        <service id="app.twig.web_image" class="Massive\Component\Web\ImageTwigExtension">
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

##### 1. Simple image tag

```twig
<img alt="Test" title="Test" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">
```

This could be:

```twig
{{ get_image(image, 'sulu-100x100') }}
```

##### 2. Complex image tag

```twig
<img alt="Logo"
     title="Description"
     src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"
     srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"
     sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw"
     id="image-id"
     class="image-class">
```

This could be:

```twig
{{ get_image(image, {
    src: 'sulu-400x400',
    srcset: 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
    sizes: '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw',
    id: 'image-id',
    class: 'image-class',
    alt: 'Logo'
}) }}
```

##### 3. Picture tag

```twig
<picture>
    <source media="(max-width: 1024px)"
            srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"
            sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">
    <source media="(max-width: 650px)"
            srcset="/uploads/media/sulu-400x400/01/image.jpg?v=1-0 1024w, /uploads/media/sulu-170x170/01/image.jpg?v=1-0 800w, /uploads/media/sulu-100x100/01/image.jpg?v=1-0 460w"
            sizes="(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw">
    <img alt="Title"
         title="Description"
         src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0"
         class="image-class">
</picture>
```

This could be:

```twig
{{ get_image(image,
    {
        src: 'sulu-400x400',
        class: 'image-class',
    },
    {
        '(max-width: 1024px)': {
            srcset: 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
            sizes: '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw'
        },
        '(max-width: 650px)': {
            srcset: 'sulu-400x400 1024w, sulu-170x170 800w, sulu-100x100 460w',
            sizes: '(max-width: 1024px) 100vw, (max-width: 800px) 100vw, 100vw'
        }
    }
) }}
```
