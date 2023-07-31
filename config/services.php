<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Knp\Bundle\TimeBundle\DateTimeFormatter;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
            ->set('time.datetime_formatter', DateTimeFormatter::class)
                ->args([service('translator')])
                ->tag('twig.runtime')
            ->alias(DateTimeFormatter::class, 'time.datetime_formatter')
    ;
};
