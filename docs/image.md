# Web Image Twig Extension

The web image twig extension gives you a simple and efficient way to handle your image over twig.

## Setup

### Service Registration

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yml
services:
    Sulu\Twig\Extensions\ImageTwigExtension: ~
```

If autoconfigure is not active you need to tag it with [twig.extension](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option).

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

The `get_lazy_image` twig function accepts the same parameters as the `get_image` function.
It will render the img attributes `src` and `srcset` as `data-src` and `data-srcset`.
Values of `src` and `srcset` are set to a placeholder svg image of the configured `placeholderPath`

Use for example [lazysizes](https://github.com/aFarkas/lazysizes) JS Library to load the images when they are visible.

```twig
<img alt="Test" title="Test" data-src="/images/placeholders/sulu-100x100.svg" src="/uploads/media/sulu-100x100/01/image.jpg?v=1-0">
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

With the `has_lazy_image()` twig function you could check if the current rendered code includes one ore more lazy images.

```twig
{% if has_lazy_image() %}
    <script src="lazy.js"></script>
{% endif %}
```
