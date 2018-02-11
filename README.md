# Friendly ago dates ("5 minutes ago")!

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

In Symfony controllers

Let's suppose you want to return a custom response like JSON, 
you can use the DateTimeFormatter service in your controllers.
```
public function yourAction()
{
    ...
    $dateTimeFormatter = $this->get('time.datetime_formatter');
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
