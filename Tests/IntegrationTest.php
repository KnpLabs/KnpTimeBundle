<?php

namespace Knp\Bundle\TimeBundle\Tests;

use Knp\Bundle\TimeBundle\KnpTimeBundle;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\RouteCollectionBuilder;

class IntegrationTest extends TestCase
{
    public function testServiceWiring(): void
    {
        $kernel = new TimeBundleIntegrationTestKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $result = $container->get('public.twig')->render('@integration_test/template.twig', [
            'yesterday' => (new \DateTime('-1 day'))
        ]);
        $this->assertSame('1 day ago', $result);
    }
}

abstract class AbstractTimeBundleIntegrationTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new KnpTimeBundle()
        ];
    }
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->loadFromExtension('framework', [
            'secret' => 'foo',
            'router' => [
                'utf8' => true,
            ],
        ]);
        $container->loadFromExtension('twig', [
            'paths' => [
                __DIR__.'/fixtures' => 'integration_test',
            ],
            'strict_variables' => true,
            'exception_controller' => null,
        ]);
        // avoid logging request logs
        $container->register('logger', Logger::class)
            ->setArgument(0, LogLevel::EMERGENCY);
        $container->setAlias('public.twig', new Alias('twig', true));
    }
    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/cache'.spl_object_hash($this);
    }
    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/logs'.spl_object_hash($this);
    }
}
if (Kernel::VERSION_ID < 50100) {
    class TimeBundleIntegrationTestKernel extends AbstractTimeBundleIntegrationTestKernel {
        protected function configureRoutes(RouteCollectionBuilder $routes): void
        {
            $routes->add('/foo', 'kernel:'.(parent::VERSION_ID >= 40100 ? ':' : '').'renderFoo');
        }
    }
} else {
    class TimeBundleIntegrationTestKernel extends AbstractTimeBundleIntegrationTestKernel {
        protected function configureRoutes(RoutingConfigurator $routes): void
        {
            $routes->add('/foo', 'kernel:'.(parent::VERSION_ID >= 40100 ? ':' : '').'renderFoo');
        }
    }
}
