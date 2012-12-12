# Provide a time helper to Symfony2 projects.

## INSTALLATION
### Composer

    composer require knplabs/knp-time-bundle
    composer --dev update knplabs/knp-time-bundle
    
### Submodule
Add it to your Symfony Project using git:

    git submodule add git://github.com/KnpLabs/KnpTimeBundle.git MyProject/vendor/bundles/Knp/Bundle/TimeBundle

### Deps

    [KnpTimeBundle]
        git=https://github.com/KnpLabs/KnpTimeBundle.git
        target=bundles/Knp/Bundle/TimeBundle

Register the namespaces:

```php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Knp\Bundle' => __DIR__.'/../vendor/bundles',
    // ...
));
```

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

Enable the helper in your config.yml:

```yaml
# app/config/config.yml
knp_time: ~      # Enable the helper for use in templates
```

## USAGE

```php
<?php
// Use the helper with Php
echo $view['time']->diff($dateTime); // returns something like "3 minutes ago"
```

```html+jinja
// Use the helper with twig
{{ time_diff(DateTimeObject) }}
```

### Note:

If you are using a different language code than two letters (en_US for example) then
should copy the TimeBundle's language files and rename the middle part according to your language:

    from:
    MyProject/vendor/bundles/Knp/Bundle/TimeBundle/Resources/translations/time.en.xliff
    MyProject/vendor/bundles/Knp/Bundle/TimeBundle/Resources/translations/time.fr.xliff

    to:
    MyProject/app/Resources/translations/time.en_US.xliff
    MyProject/app/Resources/translations/time.fr_FR.xliff

Don't forget to clear your cache afterwards.

