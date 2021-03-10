<?php

namespace Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App;

use Psr\Log\NullLogger;
use Symfony\Bridge\PsrHttpMessage\Bundle\PsrHttpMessageBundle;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Controller\PsrRequestController;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Service\AllFactories;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends SymfonyKernel
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

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes
            ->add('server_request', '/server-request')->controller([PsrRequestController::class, 'serverRequestAction'])->methods(['GET'])
            ->add('request', '/request')->controller([PsrRequestController::class, 'requestAction'])->methods(['POST'])
            ->add('message', '/message')->controller([PsrRequestController::class, 'messageAction'])->methods(['PUT'])
        ;
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'router' => ['utf8' => true],
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

        $container->extension('psr_http_message', $bundleConfig);

        $container->services()
            ->set('logger', NullLogger::class)
        ;

        if ('minimal' !== $this->implementation) {
            $container->services()
                ->set(PsrRequestController::class)->public()->autowire()
                ->set(AllFactories::class)->public()->autowire()
            ;
        }
    }
}
