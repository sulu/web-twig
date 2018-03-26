# Web Component Twig Extension

The web component twig extension in connection with [web-js](https://github.com/massiveart/web-js) 
gives you a simple and efficient way to handle your javascript components over twig.

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
        <service id="app.web_components" class="Massive\Component\Web\ComponentTwigExtension">
            <tag name="twig.extension" />
        </service>
    </services>
</container>
```

**yml**

```yml
services:
    app.web_components:
        class: Massive\Component\Web\ComponentTwigExtension
        tags:
            - { name: twig.extension }
```

## Usage

You can get the registered components and service call and call the
[web-js](https://github.com/massiveart/web-js) function which is recommended to be used with it.

```twig
{# Registering a component #}
<div id="{{ register_component('component') }}">
    Content
</div>

{# Registering a component with options #}
<button id="{{ register_component('modal-button', { text: 'Hello Hikaru Sulu' }) }}">
    Say Hello
</button>

{# Call a service function #}
{{ call_service('service', 'function') }}

{# Call a service function with arguments #}
{{ call_service('api', 'send', ['Hello']) }}

{# Start components and run service functions #}
<script>
    web.startComponents({{ get_components() }});
    web.callServices({{ get_services() }});
</script>
```
