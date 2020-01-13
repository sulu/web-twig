# Editor Extension

Sometimes you have very special styling for ul, ol lists or other tags which are used in a text editor
where you are not able to add class. This twig extension should help you to wrap that output in a div
or add classes to it.

## Setup

### Service Registration

The editor extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yml
services:
    Sulu\Twig\Extensions\EditorExtension: ~

    # Full configuration:
    Sulu\Twig\Extensions\EditorExtension:
        arguments:
            - { ul: 'list' }
            - 'div'
            - 'editor'
```

If autoconfigure is not active you need to tag it with [twig.extension](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option).

## Usage 'editor_classes' filter

### Basic Usage

```twig
{% set yourHtml = '<ul><li>Test</li></ul>' %}
{{ yourHtml|editor_classes }}
```

This will output:

```html
<ul class="list"><li>Test</li></ul>
```

### Specify classes

```twig
{% set yourHtml = '<ul><li>Test</li></ul>' %}
{{ yourHtml|editor_classes({ul: 'special-list'}) }}
```

This will output:

```html
<ul class="special-list"><li>Test</li></ul>
```

## Usage `editor` filter

### Basic Usage

```twig
{% set yourHtml = '<p>Test</p>' %}
{{ yourHtml|editor }}
```

This will output:

```html
<div class="editor"><p>Test</p></div>
```

### Custom Tag

```twig
{% set yourHtml = '<p>Test</p>' %}
{{ yourHtml|editor('section') }}
```

This will output:

```html
<section class="editor"><p>Test</p></section>
```

### Custom Class

```twig
{% set yourHtml = '<p>Test</p>' %}
{{ yourHtml|editor(null, 'custom') }}
```

This will output:

```html
<div class="custom"><p>Test</p></div>
```
