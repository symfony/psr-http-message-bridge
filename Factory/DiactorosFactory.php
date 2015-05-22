<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\Factory;

use Symfony\Bridge\PsrHttpMessage\PsrHttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zend\Diactoros\ServerRequest as DiactorosRequest;
use Zend\Diactoros\Response as DiactorosResponse;
use Zend\Diactoros\UploadedFile as DiactorosUploadedFile;

/**
 * Builds Psr\HttpMessage instances using the Zend Diactoros implementation.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DiactorosFactory implements PsrHttpMessageFactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function createRequest(Request $symfonyRequest)
    {
        return new DiactorosRequest(

        );
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(Response $symfonyResponse)
    {
        return new DiactorosResponse(

        );
    }

    /**
     * {@inheritdoc}
     */
    public function createUploadedFile(UploadedFile $symfonyUploadedFile)
    {
        return new DiactorosUploadedFile(
            null,
            null,
            null
        );
    }
}
