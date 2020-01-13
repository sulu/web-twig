# Intl Extension

The intl twig extension gives you a simple and efficient way to get country, language or a locale in a specific locale.

## Setup

### Service Registration

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yml
services:
    Sulu\Twig\Extensions\IntlExtension: ~
```

If autoconfigure is not active you need to tag it with [twig.extension](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option).

## Usage

### Get country

You can get a country in a specific language:

```twig
{{ intl_country('de') }}
{{ intl_country('de', 'de') }}
{{ intl_alpha3_country('deu') }}
{{ intl_alpha3_country('deu', 'de') }}
```

Output:

```html
Germany
Deutschland
Germany
Deutschland
```

You can also get a list of countries by using `intl_countries('de')` or `intl_alpha3_countries('de')`.

### Get language

You can get a language in a specfic language:

```twig
{{ intl_language('de') }}
{{ intl_language('de', null, 'de') }}
{{ intl_language('de', 'AT') }}
{{ intl_language('de', 'AT', 'de') }}
{{ intl_alpha3_language('deu') }}
{{ intl_alpha3_language('deu', 'de') }}
```

Output:

```html
German
Deutsch
Austrian German
Österreichisches Deutsch
German
Deutsch
```

You can also get a list of languages by using `intl_languages('de')` or `intl_alpha3_languages('deu', 'de')`.

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

