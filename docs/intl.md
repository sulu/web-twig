# Intl Twig Extension

The intl twig extension gives you a simple and efficient way to get country, language or a locale in a specific locale.

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
        <service id="app.twig.web_intl" class="Massive\Component\Web\IntlTwigExtension">
            <tag name="twig.extension" />
        </service>
    </services>
</container>
```

**yml**

```yml
services:
    app.twig.web_intl:
        class: Massive\Component\Web\IntlTwigExtension
        tags:
            - { name: twig.extension }
```

## Usage

### Get country

You can get a country in a specific language:

```twig
{{ intl_country('de') }}
{{ intl_country('de', 'de') }}
```

Output:

```html
Germany
Deutschland
```

You can also get a list of countries by using `intl_countries('de')`.

### Get language

You can get a language in a specfic language:

```twig
{{ intl_language('de') }}
{{ intl_language('de', null, 'de') }}
{{ intl_language('de', 'AT') }}
{{ intl_language('de', 'AT', 'de') }}
```

Output:

```html
German
Deutsch
Austrian German
Österreichisches Deutsch
```

You can also get a list of languages by using `intl_languages('de')`.

### Get locale

You can get a locale in a specfic language:

```twig
{{ intl_locale('de') }}
{{ intl_locale('de', 'de') }}
{{ intl_locale('de_AT') }}
{{ intl_locale('de_AT' 'de') }}
```

Output:

```html
German
Deutsch
German (Austria)
Deutsch (Österreich)
```

You can also get a list of locales by using `intl_locales('de')`.

### Get icu locale

Convert a de-at locale to a valid de_AT:

```twig
{{ 'de-at'|intl_icu_locale }}
```

Output:

```html
de_AT
```

