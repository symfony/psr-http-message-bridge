<?php

namespace Symfony\Bridge\PsrHttpMessage\Factory;

use Psr\Http\Message\RequestInterface;
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
    public function createRequest(RequestInterface $psrHttpMessageRequest)
    {
        // TODO: Implement createRequest() method.
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(ResponseInterface $psrHttpMessageResponse)
    {
        // TODO: Implement createResponse() method.
    }
}
