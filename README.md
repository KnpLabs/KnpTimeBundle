# Friendly *ago* dates ("5 minutes ago")!

This bundle does one simple job: takes dates and gives you friendly "2 hours ago"-type messages. Woh!

```html+jinja
Last edited {{ post.updatedAt|ago }}
<-- Last edited 1 week ago -->
```

Want to see it used in a screencast 🎥? Check out SymfonyCasts: https://symfonycasts.com/screencast/symfony-doctrine/ago

The date formatted can be translated into any language, and many are supported out of the box.

## INSTALLATION

Use Composer to install the library:

```
composer require knplabs/knp-time-bundle
```

Woo! You did it! Assuming your project uses Symfony Flex, the
bundle should be configured and ready to go. If not, you
can enable `Knp\Bundle\TimeBundle\KnpTimeBundle` manually.

## USAGE

In Twig:

```twig
{{ someDateTimeVariable|ago }}

... or use the equivalent function:
{{ time_diff(someDateTimeVariable) }}
```

Note: the "ago" filter works fine for dates in the future, too. 

### In controllers

You can also "ago" dates inside PHP by autowiring the `Knp\Bundle\TimeBundle\DateTimeFormatter` service:

```
use Knp\Bundle\TimeBundle\DateTimeFormatter;
// ...

public function yourAction(DateTimeFormatter $dateTimeFormatter)
{
    $someDate = new \DateTime('2017-02-11'); //or $entity->publishedDate()
    $now = new \DateTime();
    
    $agoTime = $dateTimeFormatter->formatDiff($someDate, $now);
    return $this->json([
        ...
        'published_at' => $agoTime
        ...
    ]);
}
```

## Controlling the Translation Locale

The bundle will automatically use the current locale when translating
the "ago" messages. However, you can override the locale:

```twig
{{ someDateTimeVariable|ago(locale='es') }}
```

## TESTS

If you want to run tests, please check that you have installed dev dependencies.

```bash
./vendor/bin/simple-phpunit
```

## Maintainers

Anyone can contribute to this repository (and it's warmly welcomed!). The following
people maintain and can merge into this library:

 - [@akovalyov](https://github.com/akovalyov)
 - [@weaverryan](https://github.com/weaverryan)
 - [@NicolasNSSM](https://github.com/NicolasNSSM)
