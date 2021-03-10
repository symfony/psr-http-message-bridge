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

use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\App\Service\AllFactories;

final class AutoControllerTest extends ControllerTest
{
    public function testImplementationIsNyholm()
    {
        self::bootKernel();

        self::assertInstanceOf(Psr17Factory::class, self::$container->get(AllFactories::class)->getServerRequestFactory());
    }

    protected static function getImplementation(): string
    {
        if (!class_exists(Psr17Factory::class)) {
            self::markTestSkipped('This test requires nyholm/psr7.');
        }

        return 'auto';
    }
}
