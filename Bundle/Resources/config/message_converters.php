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

use Symfony\Bridge\PsrHttpMessage\ArgumentValueResolver\PsrServerRequestResolver;
use Symfony\Bridge\PsrHttpMessage\EventListener\PsrResponseListener;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('psr_http_message.response_listener', PsrResponseListener::class)
            ->args([new Reference('psr_http_message.http_foundation_factory')])
            ->tag('kernel.event_subscriber')

        ->set('psr_http_message.server_request_resolver', PsrServerRequestResolver::class)
            ->args([new Reference('psr_http_message.psr_http_factory')])
            ->tag('controller.argument_value_resolver')
    ;
};
