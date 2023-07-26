<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Knp\Bundle\TimeBundle\Twig\Extension\TimeExtension;

return static function (ContainerConfigurator $container): void {
    $container
        ->services()
            ->set('.time.twig.extension.time', TimeExtension::class)
                ->tag('twig.extension')
    ;
};
