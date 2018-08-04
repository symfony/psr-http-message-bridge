<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\Tests\Factory;

use Http\Factory\Diactoros\ResponseFactory;
use Http\Factory\Diactoros\ServerRequestFactory;
use Http\Factory\Diactoros\StreamFactory;
use Http\Factory\Diactoros\UploadedFileFactory;
use Http\Factory\Diactoros\UriFactory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Symfony\Bridge\PsrHttpMessage\Factory\Psr7Factory;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Psr7FactoryTest extends BasePsr7FactoryTest
{
    function getFactory(): HttpMessageFactoryInterface
    {
        return new Psr7Factory(
            new ServerRequestCreator(
                new ServerRequestFactory(),
                new UriFactory(),
                new UploadedFileFactory(),
                new StreamFactory()
            ),
            new ResponseFactory(),
            new StreamFactory()
        );
    }
}
