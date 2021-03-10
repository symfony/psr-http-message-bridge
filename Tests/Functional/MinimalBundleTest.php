<?php

namespace Functional;

use Laminas\Diactoros\ServerRequestFactory;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Kernel;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Kernel44;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;
use Symfony\Component\HttpKernel\KernelInterface;

final class MinimalBundleTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        if (class_exists(Psr17Factory::class) || class_exists(ServerRequestFactory::class)) {
            self::markTestSkipped('This test requires that no PSR-7 implementation in installed.');
        }
    }

    public function testMinimalBundle()
    {
        self::bootKernel();

        self::assertFalse(self::$container->has(HttpMessageFactoryInterface::class));
        self::assertInstanceOf(HttpFoundationFactory::class, self::$container->get(HttpFoundationFactoryInterface::class));
    }

    protected static function getKernelClass(): string
    {
        return SymfonyKernel::VERSION_ID >= 50200 ? Kernel::class : Kernel44::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        if (null === static::$class) {
            static::$class = static::getKernelClass();
        }

        return new static::$class('minimal');
    }
}
