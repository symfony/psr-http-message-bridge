<?php

namespace Symfony\Bridge\PsrHttpMessage\Factory;

use Symfony\Bridge\PsrHttpMessage\PsrHttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response as DiactorosResponse;

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
    public static function createRequest(Request $symfonyRequest)
    {
        return new ServerRequest(

        );
    }

    /**
     * {@inheritdoc}
     */
    public static function createResponse(Response $symfonyResponse)
    {
        return new DiactorosResponse(

        );
    }
}
