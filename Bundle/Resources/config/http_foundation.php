<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('psr_http_message.http_foundation_factory', HttpFoundationFactory::class)
            ->args(['%psr_http_message.response_buffer%'])

        ->alias(HttpFoundationFactoryInterface::class, 'psr_http_message.http_foundation_factory')
    ;
};
