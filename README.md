# knplabs/knp-time-bundle

Friendly *ago*/*until* dates ("5 minutes ago" or "in 5 minutes") and *durations* ("2 mins")!

```twig
Last edited: {{ post.updatedAt|time_diff }} <!-- Last edited: 1 week ago -->

Event date: {{ event.date|time_diff }} <!-- Event date: in two weeks -->

Read time: {{ post.readTimeInSeconds|duration }} <!-- Read time: 2 minutes -->

Age: {{ user.birthdate|age }} <!-- Age: 30 years old -->
```

Want to see it used in a screencast 🎥? Check out SymfonyCasts: https://symfonycasts.com/screencast/symfony-doctrine/ago

The formatted date/duration can be translated into any language, and many are supported out of the box.

## Installation

Use Composer to install the library:

```
composer require knplabs/knp-time-bundle
```

Woo! You did it! Assuming your project uses Symfony Flex, the
bundle should be configured and ready to go. If not, you
can enable `Knp\Bundle\TimeBundle\KnpTimeBundle` manually.

## Usage

### Twig

Time formatting:

```twig
{{ someDateTimeVariable|time_diff }} {# 2 weeks ago #}

{# |ago is an alias for |time_diff #}
{{ someDateTimeVariable|ago }} {# 1 second ago #}

{# ... or use the equivalent function: #}
{{ time_diff(someDateTimeVariable) }} {# in 2 months #}
```

> **Note**: the `time_diff` filter/function and `ago` alias works fine for dates in the future, too. 

Duration formatting:

```twig
{{ someDurationInSeconds|duration }} {# 2 minutes #}
```

Age formatting:

```twig
{# with filter: #}
Age: {{ user.birthdate|age }} {# Age: 30 years old #}

{# ... or use the equivalent function: #}
Age: {{ age(user.birthdate) }} {# Age: 30 years old #}
```

### Service

You can also format dates and durations in your services/controllers by autowiring/injecting the
`Knp\Bundle\TimeBundle\DateTimeFormatter` service:

```php
use Knp\Bundle\TimeBundle\DateTimeFormatter;
// ...

public function yourAction(DateTimeFormatter $dateTimeFormatter)
{
    $someDate = new \DateTimeImmutable('-2 years'); // or $entity->publishedDate()
    $toDate = new \DateTimeImmutable('now');

    $agoTime = $dateTimeFormatter->formatDiff($someDate, $toDate); // $toDate parameter is optional and defaults to "now"

    $readTime = $dateTimeFormatter->formatDuration(64); // or $entity->readTimeInSeconds()

    $ageTime = $dateTimeFormatter->formatAge($someDate, $toDate); // $toDate parameter is optional and defaults to "now"

    return $this->json([
        //  ...
        'published_at' => $agoTime, // 2 years ago
        'read_time' => $readTime, // 1 minute
        // ...
    ]);
}
```

## Controlling the Translation Locale

The bundle will automatically use the current locale when translating
the "time_diff" ("ago") and "duration" messages. However, you can override
the locale:

```twig
{{ someDateTimeVariable|time_diff(locale='es') }}

{{ someDurationInSeconds|duration(locale='es') }}

{{ someDateTimeVariable|age(locale='es') }}
```

## Tests

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
