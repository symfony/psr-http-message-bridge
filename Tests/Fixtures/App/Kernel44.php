<?php

namespace Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App;

use Psr\Log\NullLogger;
use Symfony\Bridge\PsrHttpMessage\Bundle\PsrHttpMessageBundle;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Controller\PsrRequestController;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Service\AllFactories;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel44 extends SymfonyKernel
{
    use MicroKernelTrait;

    private $implementation;

    public function __construct(string $implementation)
    {
        $this->implementation = $implementation;

        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new PsrHttpMessageBundle();
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return parent::getCacheDir().'/'.$this->implementation;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $routes->add('/server-request', PsrRequestController::class.'::serverRequestAction')->setMethods(['GET']);
        $routes->add('/request', PsrRequestController::class.'::requestAction')->setMethods(['POST']);
        $routes->add('/message', PsrRequestController::class.'::messageAction')->setMethods(['PUT']);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->loadFromExtension('framework', [
            'secret' => 'for your eyes only',
            'test' => true,
        ]);

        $bundleConfig = [
            'message_converters' => ['enabled' => true],
        ];
        if ('auto' !== $this->implementation && 'minimal' !== $this->implementation) {
            $bundleConfig['message_factories'] = [
                'enabled' => true,
                'implementation' => $this->implementation,
            ];
        }

        $container->loadFromExtension('psr_http_message', $bundleConfig);

        $container->register('logger', NullLogger::class);

        if ('minimal' !== $this->implementation) {
            $container->register(PsrRequestController::class)->setPublic(true)->setAutowired(true);
            $container->register(AllFactories::class)->setPublic(true)->setAutowired(true);
        }
    }
}
