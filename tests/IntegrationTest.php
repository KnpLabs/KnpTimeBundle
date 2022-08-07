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

class IntegrationTest extends TestCase
{
    /**
     * @var TimeBundleIntegrationTestKernel
     */
    private $kernel;

    public function testServiceWiring(): void
    {
        $result = $this->kernel->getContainer()->get('public.twig')->render('@integration_test/template.twig', [
            'yesterday' => (new \DateTime('-1 day'))
        ]);

        $this->assertSame('1 day ago', $result);
    }

    public function testLocalTranslation()
    {
        $result = $this->kernel->getContainer()->get('public.twig')->render('@integration_test/templateSpecificLocale.twig', [
            'yesterday' => (new \DateTime('-1 day')),
            'monthAgo' => (new \DateTime('-1 month'))
        ]);

        $this->assertStringContainsString('il y a 1 jour', $result);
        $this->assertStringContainsString('il y a 1 mois', $result);
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
            'http_method_override' => false,
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

class TimeBundleIntegrationTestKernel extends AbstractTimeBundleIntegrationTestKernel {
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->add('/foo', 'kernel::renderFoo');
    }
}
