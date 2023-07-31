<?php

namespace Knp\Bundle\TimeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Twig\Extension\AbstractExtension;

/**
 * @internal
 */
final class KnpTimeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        if (class_exists(AbstractExtension::class)) {
            $loader->load('twig.php');
        }
    }
}
