# Friendly ago dates ("5 minutes ago")!

[![Build Status](https://travis-ci.org/KnpLabs/KnpTimeBundle.svg?branch=master)](https://travis-ci.org/KnpLabs/KnpTimeBundle)

This bundle does one simple job: takes dates and gives you friendly "2 hours ago"-type messages. Woh!

```html+jinja
Last edited {{ post.updatedAt|ago }}
<-- Last edited 1 week ago -->
```

The date formatted can be translated into any language, and may are supported out of the box.

## INSTALLATION via Composer

    composer require knplabs/knp-time-bundle

## CONFIGURATION
Register the bundle:

```php
<?php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\TimeBundle\KnpTimeBundle(),
    );
    // ...
}
```

Enable the translation component if you haven't already done it:

```yaml
# app/config/config.yml
framework:
    # ...
    translator:      { fallback: '%locale%' } # uncomment this line if you see this line commented
```


## USAGE

In PHP!

```php
<?php
// Use the helper with Php
echo $view['time']->diff($dateTime); // returns something like "3 minutes ago"
```

In Twig!

```html+jinja
{{ someDateTimeVariable|ago }}
... or use the equivalent function
{{ time_diff(someDateTimeVariable) }}
```

## TESTS

If you want to run tests, please check that you have installed dev dependencies.

```bash
./vendor/bin/phpunit
```

## Maintainers

Anyone can contribute to this repository (and it's warmly welcomed!). The following
people maintain and can merge into this library:

* akovalyov
* weaverryan
