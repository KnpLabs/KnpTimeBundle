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
    /**
     * @var TimeBundleIntegrationTestKernel
     */
    private $kernel;

    public function testServiceWiring()
    {
        $result = $this->kernel->getContainer()->get('public.twig')->render('@integration_test/template.twig', [
            'yesterday' => (new \DateTime('-1 day'))
        ]);

        $this->assertSame('1 day ago', $result);
    }

    public function testLocalTranslation()
    {
        $result = $this->kernel->getContainer()->get('public.twig')->render('@integration_test/templateSpecificLocale.twig', [
            'yesterday' => (new \DateTime('-1 day'))
        ]);

        $this->assertSame('il y a 1 jour', $result);
    }

    protected function setUp(): void
    {
        $this->kernel = new TimeBundleIntegrationTestKernel();
        $this->kernel->boot();
    }
}

abstract class AbstractTimeBundleIntegrationTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new KnpTimeBundle()
        ];
    }
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
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
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/cache'.spl_object_hash($this);
    }
    public function getLogDir()
    {
        return sys_get_temp_dir().'/logs'.spl_object_hash($this);
    }
}
if (Kernel::VERSION_ID < 50100) {
    class TimeBundleIntegrationTestKernel extends AbstractTimeBundleIntegrationTestKernel {
        protected function configureRoutes(RouteCollectionBuilder $routes)
        {
            $routes->add('/foo', 'kernel:'.(parent::VERSION_ID >= 40100 ? ':' : '').'renderFoo');
        }
    }
} else {
    class TimeBundleIntegrationTestKernel extends AbstractTimeBundleIntegrationTestKernel {
        protected function configureRoutes(RoutingConfigurator $routes)
        {
            $routes->add('/foo', 'kernel:'.(parent::VERSION_ID >= 40100 ? ':' : '').'renderFoo');
        }
    }
}
