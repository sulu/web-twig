# Icon Extension

The icon extension gives you a simple way to render icomoon icons.

## Setup

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yaml
services:
    Sulu\Twig\Extensions\IconExtension: ~
```

Or a more complex configuration example:

```yaml
services:
    Sulu\Twig\Extensions\IconExtension:
        arguments:
            $iconSets:
                # icon font:
                default:
                    type: 'font'
                    # the following options are optional but need to match your icomoon export settings:
                    # className: 'icon'
                    # classPrefix: 'icon-'
                    # classSuffix: ''
                # svg icons:
                other:
                    type: 'svg'
                    path: '/website/fonts/icomoon-sulu/symbol-defs.svg'

                    # the following options are optional but need to match your icomoon export settings:
                    # className: 'icon' 
                    # classPrefix: 'icon-' 
                    # classSuffix: ''
            $defaultAttributes:
                role: 'none'
```

## Usage

### Icon Font

```yaml
services:
    Sulu\Twig\Extensions\IconExtension:
        # the following configuration is default
        arguments:
            $iconSets:
                default:
                    type: 'font'
```

Now you can use the twig extension the following way:

```twig
{{ get_icon('test') }}
```

This will output:

```html
<span class="icon icon-test"></span>
```

### SVG Icon

Icomoon also support svg icon. This twig extension target to make the switch easy
by just changing the configuration:

```yaml
services:
    Sulu\Twig\Extensions\IconExtension:
        arguments:
            $iconSets:
                default:
                    type: 'svg'
                    path: '/path/to/symbol-defs.svg'
```

Call the twig extension again the following way:

```twig
{{ get_icon('test') }}
```

Will now output the icon the following way:

```html
<svg class="icon icon-test"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>
```

### Additional class and Icon Sets

```yaml
services:
    Sulu\Twig\Extensions\IconExtension:
        arguments:
            $iconSets:
                default:
                    type: 'font'
                    className: 'my-icon'
                    classPrefix: 'my-icon-'
                    classSuffix: '-new'
                other:
                    type: 'svg'
                    path: '/path/to/symbol-defs.svg'
                    className: 'my-icon'
                    classPrefix: 'my-icon-'
                    classSuffix: '-new'
```

If you use icomoon as icon font you can use the twig extension the following way:

```twig
<!-- Icon Font -->
{{ get_icon('test', 'other-class') }}

<!-- SVG Icons -->
{{ get_icon('test', 'other-class', 'other') }}
```

This will output:

```html
<!-- Icon Font -->
<span class="other-class my-icon my-icon-test-new"></span>

<!-- SVG Icons -->
<svg class="other-class my-icon my-icon-test-new"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>
```

As you see above the format for the classes is the following:

```
{additionaClass} {className} {classPrefix}{icon}{classSuffix}
```

### Add additional attributes

Not only a class can be passed you can also add any other attributes:

```twig
<!-- Icon Font -->
{{ get_icon('test', { role: 'none'}) }}

<!-- SVG Icons -->
{{ get_icon('test', { role: 'none'}, 'other') }}
```

This will output:

```html
<!-- Icon Font -->
<span role="none" class="icon icon-test"></span>

<!-- SVG Icons -->
<svg role="none" class="icon icon-test"><use xlink:href="/path/to/symbol-defs.svg#test"></use></svg>
```

You can also configure default attributes in the service registration the following way:

```yaml
services:
    Sulu\Twig\Extensions\IconExtension:
        arguments:
            $defaultAttributes:
                role: 'none'
```
