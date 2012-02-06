# Provide a time helper to Symfony2 projects.

## INSTALLATION

Add it to your Symfony Project:

    git submodule add git://github.com/KnpLabs/KnpTimeBundle.git MyProject/vendor/bundles/Knp/Bundle/TimeBundle
    
Or Using Deps (Add the following to deps file and run bin/vendors):

    [KnpTimeBundle]
    git=git://github.com/KnpLabs/KnpTimeBundle.git
    target=bundles/Knp/Bundle/TimeBundle


Add it to your app/autoload.php:

    $loader->registerNamespaces(array(
        // Symfony Core Namespaces
        'Symfony'          => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
        // ...
        // Depencies
        'Knp'              => __DIR__.'/../vendor/bundles',
        // ...
        // own Namespaces
        // ...
    ));

Add it to your app/AppKernel.php:

    public function registerBundles()
    {
        $bundles = array(
            // Symfony Core Stuff
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // ...
            // dependencies
            new Knp\Bundle\TimeBundle\KnpTimeBundle(),
            // own bundles
            // ...
        );

        // ...
    }

Enable the helper in your config.yml:

    knp_time: ~      # Enable the helper for use in templates

## USAGE

    // Use the helper with Php
    echo $view['time']->diff($dateTime); // returns something like "3 minutes ago"
    // Use the helper with twig
    {{ time_diff(DateTimeObject) }}

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

