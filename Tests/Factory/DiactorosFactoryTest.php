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

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DiactorosFactoryTest extends BasePsr7FactoryTest
{
    public function setup()
    {
        if (!class_exists('Zend\Diactoros\ServerRequestFactory')) {
            $this->markTestSkipped('Zend Diactoros is not installed.');
        }

        parent::setup();
    }

    function getFactory(): HttpMessageFactoryInterface
    {
        return new DiactorosFactory();
    }
}
