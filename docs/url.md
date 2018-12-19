# Url Twig Extension

The url twig extension gives you a simple and efficient way to get specific parts of urls.

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
        <service id="app.twig.web_url" class="Massive\Component\Web\UrlTwigExtension">
            <tag name="twig.extension" />
        </service>
    </services>
</container>
```

**yml**

```yml
services:
    app.twig.web_url:
        class: Massive\Component\Web\UrlTwigExtension
        tags:
            - { name: twig.extension }
```
