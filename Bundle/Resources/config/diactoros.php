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

use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laminas\Diactoros\UriFactory;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('psr_http_message.diactoros.request_factory', RequestFactory::class)
        ->set('psr_http_message.diactoros.response_factory', ResponseFactory::class)
        ->set('psr_http_message.diactoros.server_request_factory', ServerRequestFactory::class)
        ->set('psr_http_message.diactoros.stream_factory', StreamFactory::class)
        ->set('psr_http_message.diactoros.uploaded_file_factory', UploadedFileFactory::class)
        ->set('psr_http_message.diactoros.uri_factory', UriFactory::class)

        ->alias('psr_http_message.psr.request_factory', 'psr_http_message.diactoros.request_factory')
        ->alias('psr_http_message.psr.response_factory', 'psr_http_message.diactoros.response_factory')
        ->alias('psr_http_message.psr.server_request_factory', 'psr_http_message.diactoros.server_request_factory')
        ->alias('psr_http_message.psr.stream_factory', 'psr_http_message.diactoros.stream_factory')
        ->alias('psr_http_message.psr.uploaded_file_factory', 'psr_http_message.diactoros.uploaded_file_factory')
        ->alias('psr_http_message.psr.uri_factory', 'psr_http_message.diactoros.uri_factory')
    ;
};
