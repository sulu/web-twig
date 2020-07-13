# Icon Extension

The icon extension gives you a simple way to render icomoon icons.

## Setup

The twig extension need to be registered as [symfony service](http://symfony.com/doc/current/service_container.html).

```yaml
services:
    Sulu\Twig\Extensions\IconExtension:
        arguments:
            $iconSets:
                default:
                    type: 'font' # or 'svg'
                    # for svg also a path to symbol-defs file is needed:
                    # path: '/website/fonts/icomoon-sulu/symbol-defs.svg'

                    # the following options are optional but need to match your icomoon export settings:
                    # className: 'icon' 
                    # classPrefix: 'icon-' 
                    # classSuffix: ''
```

## Usage

### Icon Font

```yaml
services:
    Sulu\Twig\Extensions\IconExtension:
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
