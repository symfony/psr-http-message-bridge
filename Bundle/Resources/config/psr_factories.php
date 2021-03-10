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

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('psr_http_message.psr_http_factory', PsrHttpFactory::class)
        ->args([
            new Reference('psr_http_message.psr.server_request_factory'),
            new Reference('psr_http_message.psr.stream_factory'),
            new Reference('psr_http_message.psr.uploaded_file_factory'),
            new Reference('psr_http_message.psr.response_factory'),
        ])

        ->alias(HttpMessageFactoryInterface::class, 'psr_http_message.psr_http_factory')

        ->alias(RequestFactoryInterface::class, 'psr_http_message.psr.request_factory')
        ->alias(ResponseFactoryInterface::class, 'psr_http_message.psr.response_factory')
        ->alias(ServerRequestFactoryInterface::class, 'psr_http_message.psr.server_request_factory')
        ->alias(StreamFactoryInterface::class, 'psr_http_message.psr.stream_factory')
        ->alias(UploadedFileFactoryInterface::class, 'psr_http_message.psr.uploaded_file_factory')
        ->alias(UriFactoryInterface::class, 'psr_http_message.psr.uri_factory')
    ;
};
