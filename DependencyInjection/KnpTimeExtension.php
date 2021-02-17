<?php

namespace Knp\Bundle\TimeBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Templating\Helper\Helper;
use Twig\Extension\AbstractExtension;

class KnpTimeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (class_exists(AbstractExtension::class)) {
            $loader->load('twig.xml');
        }

        if (class_exists(Helper::class)) {
            $loader->load('templating.xml');
        }
    }
}
