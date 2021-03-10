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

use Nyholm\Psr7\Factory\Psr17Factory;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('psr_http_message.nyholm.factory', Psr17Factory::class)

        ->alias('psr_http_message.psr.request_factory', 'psr_http_message.nyholm.factory')
        ->alias('psr_http_message.psr.response_factory', 'psr_http_message.nyholm.factory')
        ->alias('psr_http_message.psr.server_request_factory', 'psr_http_message.nyholm.factory')
        ->alias('psr_http_message.psr.stream_factory', 'psr_http_message.nyholm.factory')
        ->alias('psr_http_message.psr.uploaded_file_factory', 'psr_http_message.nyholm.factory')
        ->alias('psr_http_message.psr.uri_factory', 'psr_http_message.nyholm.factory')
    ;
};
