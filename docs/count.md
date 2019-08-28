# Count Extension

The count twig extension gives you a simple and efficient way to have a global counter in your twig template.

## Setup

### Service Registration

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yml
services:
    Sulu\Twig\Extensions\CountExtension: ~
```

If autoconfigure is not active you need to tag it with [twig.extension](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option).

## Usage

### Counter

You can increase and get the current counter with:

```twig
{{ counter('example') }}
{{ counter('example') }}
{{ counter('test') }}
{{ counter('test') }}
{% do reset_counter('example') %}
{{ counter('example') }}
```

Output:

```html
1
2
1
2
1
```

A more real use case would be to check for odd or even:

```twig
<div class="section{% if counter('section') is odd %} section--black{% endif %}">
```
