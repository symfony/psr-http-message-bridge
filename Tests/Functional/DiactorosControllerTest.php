<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\Tests\Functional;

use Laminas\Diactoros\ServerRequestFactory;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Service\AllFactories;

final class DiactorosControllerTest extends ControllerTest
{
    public function testImplementationIsDiactoros()
    {
        self::bootKernel();

        self::assertInstanceOf(ServerRequestFactory::class, self::$container->get(AllFactories::class)->getServerRequestFactory());
    }

    protected static function getImplementation(): string
    {
        if (!class_exists(ServerRequestFactory::class)) {
            self::markTestSkipped('This test requires nyholm/psr7.');
        }

        return 'diactoros';
    }
}
