# Portal Extension

The portal twig extension is inspired by [react portals](https://reactjs.org/docs/portals.html) and allow to render content at a different position.
It should help to solve z-index problems by rendering overlays outside of the other DOM elements.

## Setup

### Service Registration

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yml
services:
    Sulu\Twig\Extensions\PortalExtension: ~
```

If autoconfigure is not active you need to tag it with [twig.extension](https://symfony.com/doc/current/service_container.html#the-autoconfigure-option).

## Usage

You can use a portal to output content at another position:

```twig
<section class="containers">
    {% for i in 1..3 %}
        <div>
            {{- 'Title ' ~ i -}}
        </div>

        {% portal overlays %}
            <div>
                {{- 'Overlay ' ~ i -}}
            </div>
        {% endportal %}
    {% endfor %}
</section>

<section class="overlays">
    {{ get_portal('overlays') }}
</section>
```

Output:

```html
<section class="containers">
    <div>Title 1</div>
    <div>Title 2</div>
    <div>Title 3</div>
</section>

<section class="overlays">
    <div>Overlay 1</div>
    <div>Overlay 2</div>
    <div>Overlay 3</div>
</section>
```
