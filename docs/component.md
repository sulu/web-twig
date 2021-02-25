# Web Component Extension

[DEMO](https://github.com/sulu/web-js-twig-demo)

The web component twig extension in connection with [web-js](https://github.com/sulu/web-js) 
gives you a simple and efficient way to handle your javascript components over twig.

## Setup

### Service Registration

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yml
services:
    Sulu\Twig\Extensions\ComponentExtension: ~
```

If autoconfigure is not active you need to tag it with [twig.extension](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option).

## Usage

Every component is assigned to a DOM element. The `prepare_component` function will generate a unique id
which needs to be set to the DOM element.

To start the prepared components, you need to call [web-js](https://github.com/sulu/web-js) functions at the bottom of your HTML Document.

With the `component_list` twig function you can also add only specific html when really needed.

```twig
{# Registering a component #}
<div id="{{ prepare_component('component') }}">
    Content
</div>

{# Registering a component with options #}
<button id="{{ prepare_component('modal-button', { text: 'Hello Hikaru Sulu' }) }}">
    Say Hello
</button>

{# Output html that is only needed if a specific component was prepared #}	
{% if 'component' in get_component_list() %}	
    <script id="component-template" type="text/html">	
        <div>Template</div>	
    </script>	
{% endif %}

{# Start components and run service functions #}
<script>
    web.startComponents({{ get_components() }});
</script>
```

### Calling services

Sometimes you just want to call a `web-js` service function.
This can be achieved the following way:

```twig
{# Call a service function #}
{% do prepare_service('service', 'function') %}

{# Call a service function with arguments #}
{% do prepare_service('api', 'setApiKey', [MY_API_KEY]) %}

{# Start components and run service functions #}
<script>
    web.callServices({{ get_services() }});
</script>
```

### Force specific id

If you don't want to autogenerate an ID you can give it as an option:

```twig
<div id="{{ prepare_component('component', { id: 'my-special-id' }) }}">
    Content
</div>
```

### Handling ESI

An ESI request is a separate sub request that does not know anything about the main request. To prevent conflicts, components need to be handled differently inside of ESI requests.
If you want to use a `web-js` component inside of a ESI request, you need to add the following to your `base.html.twig`:

```twig
<head>
    {# ... #}

    <script>
        /* this array will be filled with esi components */
        var components = [];
    </script>
</head>
<body>
    {# ... #}

    {# Start components and run service functions #}
    <script>
        web.startComponents(components.concat({{ get_components() }}));
    </script>
</body>
```

In the ESI Template you need to add the following:

```twig
{# before first component: #}
{% do set_component_prefix('unique_prefix') %}

{# ... your twig template with prepare_component calls here #}

{# bottom of your esi template: #}
<script>
    components = components.concat({{ get_components() }});
</script>
```
