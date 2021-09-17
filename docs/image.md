# Web Image Extension

The web image twig extension gives you a simple and efficient way to handle your image over twig.

## Setup

### Service Registration

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yml
services:
    Sulu\Twig\Extensions\ImageExtension: ~
```

If autoconfigure is not active you need to tag it with [twig.extension](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option).

**Recommended Configuration**

```yaml
Sulu\Twig\Extensions\ImageExtension:
    arguments:
        $defaultAttributes:
            loading: 'lazy'
        $defaultAdditionalTypes:
            webp: 'image/webp'
        $aspectRatio: true
        $imageFormatConfiguration: '%sulu_media.image.formats%'
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

##### 3. Simple Picture Tag

```twig
<picture>
    <source media="(max-width: 1024px)"
            srcset="/uploads/media/sulu-170x170/01/image.jpg?v=1-0">
    <source media="(max-width: 650px)"
            srcset="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">
    <img alt="Title"
         title="Description"
         src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0">
</picture>
```

```twig
{{ get_image(headerImage, 'sulu-400x400', {
    '(max-width: 1024px)': 'sulu-170x170',
    '(max-width: 650px)': 'sulu-100x100',
}) }}
```

##### 4. Complex Picture tag

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

##### 5. Lazy images

> See also 6. Native lazy loading for a modern implementation.

The `get_lazy_image` twig function accepts the same parameters as the `get_image` function.
It will render the img attributes `src` and `srcset` as `data-src` and `data-srcset`.
Values of `src` and `srcset` are set to a placeholder svg image of the configured `placeholderPath`

Use for example [lazysizes](https://github.com/aFarkas/lazysizes) JS Library to load the images when they are visible.

```twig
<img alt="Test" title="Test" src="/images/placeholders/sulu-100x100.svg" data-src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">
```

This could be:

```twig
{{ get_lazy_image(image, 'sulu-100x100') }}
```

The placeholder svg should look like this:

```svg
<?xml version="1.0" encoding="UTF-8"?>
<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"/>
```

For this you need configure the placeholder path in the service definition:

```yaml
services:
    Sulu\Twig\Extensions\ImageExtension:
        arguments:
            $placeholderPath: '/images/placeholders'
```

With the `has_lazy_image()` twig function you could check if the current rendered code includes one ore more lazy images.

```twig
{% if has_lazy_image() %}
    <script src="lazy.js"></script>
{% endif %}
```

##### 6. Native lazy loading

Browsers today have native support for [lazy-loading](https://caniuse.com/#feat=loading-lazy-attr).

You can do the following:

```twig
{{ get_image(image, {
    src: '800x',
    loading: 'lazy',
}) }}
```

or when you want by default load all images lazy use the following service definition:

```yml
services:
    Sulu\Twig\Extensions\ImageExtension:
        arguments:
            $defaultAttributes:
                loading: 'lazy'
```

##### 7. Webp Support

If your server supports converting images to webp you can automatically enable webp
output for the image twig functions per default the following way:

```yaml
services:
    Sulu\Twig\Extensions\ImageExtension:
        arguments:
            $defaultAdditionalTypes:
                webp: 'image/webp'
```

This will render a picture tag which look like the following:

```twig
<picture>
    <source media="(max-width: 1024px)"
            srcset="/uploads/media/sulu-170x170/01/image.webp?v=1-0"
            type="image/webp">
    <source media="(max-width: 1024px)"
            srcset="/uploads/media/sulu-170x170/01/image.jpg?v=1-0">
    <source media="(max-width: 650px)"
            srcset="/uploads/media/sulu-100x100/01/image.webp?v=1-0"
            type="image/webp">
    <source media="(max-width: 650px)"
            srcset="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">
    <source srcset="/uploads/media/sulu-100x100/01/image.webp?v=1-0"
            type="image/webp">
    <img alt="Title"
         title="Description"
         src="/uploads/media/sulu-400x400/01/image.jpg?v=1-0">
</picture>
```

You can also only activate it for a specific call:

```twig
{{ get_image(headerImage, 'sulu-400x400', {
    '(max-width: 1024px)': 'sulu-170x170',
    '(max-width: 650px)': 'sulu-100x100',
}, { webp: 'image/webp' }) }}
```

##### 8. Set width and height attribute automatically

Since Sulu 2.3 the original image width and height are saved as properties.
This allows to guess the width and height of a image format and set the respective HTML attributes.

Setting the width and height attribute allows modern browsers to avoid layer shifts
and therefore the page will not jump when images are loaded.

This feature can be activated the following way:

```yaml
services:
    Sulu\Twig\Extensions\ImageExtension:
        arguments:
            $aspectRatio: true
            $imageFormatConfiguration: '%sulu_media.image.formats%' # optional but recommended
```

For example, if the original image has a resolution of 1920x1080 and the image format is called 100x:

```twig
{{ get_image(headerImage, '100x') }}
```

The feature will automatically add a width and height attribute to the rendered image tag:

```twig
<img alt="Title" title="Description" src="/uploads/media/100x/01/image.jpg?v=1-0" width="'100" height="56">
```

The `$imageFormatConfiguration` parameter is optional. If it is not set, the extension
tries to guess the dimensions by the given format key. This will only work for format keys
in the format of 100x, x100, 100x@2x and 100x100-inset.
