# Friendly ago dates ("5 minutes ago")!

This bundle does one simple job: takes dates and gives you friendly "2 hours ago"-type messages. Woh!

```html+jinja
Last edited {{ post.updatedAt|ago }}
<-- Last edited 1 week ago -->
```

The date formatted can be translated into any language, and many are supported out of the box.

## INSTALLATION

Use Composer to install the library:

```
composer require knplabs/knp-time-bundle
```

Woo! You did it! Assuminy project uses Symfony Flex, the
bundle should be configured and ready to go. If not, you
can enable `Knp\Bundle\TimeBundle\KnpTimeBundle` manually.

## USAGE

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

 - [@akovalyov](https://github.com/akovalyov)
 - [@weaverryan](https://github.com/weaverryan)
 - [@NicolasNSSM](https://github.com/NicolasNSSM)
