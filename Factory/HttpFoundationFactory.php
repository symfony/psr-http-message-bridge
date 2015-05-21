<?php

namespace Symfony\Bridge\PsrHttpMessage\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * {@inheritdoc}
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class HttpFoundationFactory implements HttpFoundationFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public static function createRequest(ServerRequestInterface $psrHttpMessageRequest)
    {
        return new Request(

        );
    }

    /**
     * {@inheritdoc}
     */
    public static function createResponse(ResponseInterface $psrHttpMessageResponse)
    {
        return new Response(

        );
    }
}
