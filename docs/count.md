# Count Twig Extension

The count twig extension gives you a simple and efficient way to have a global counter in your twig template.

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
        <service id="app.twig.web_count" class="Massive\Component\Web\CountTwigExtension">
            <tag name="twig.extension" />
        </service>
    </services>
</container>
```

**yml**

```yml
services:
    app.twig.web_count:
        class: Massive\Component\Web\CountTwigExtension
        tags:
            - { name: twig.extension }
```

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
