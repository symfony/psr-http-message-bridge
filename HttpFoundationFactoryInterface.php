<?php

namespace Symfony\Bridge\PsrHttpMessage;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Creates Symfony Request and Response from PSR-7 ones.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface HttpFoundationFactoryInterface
{
    /**
     * Creates a Symfony Request from a PSR-7 one.
     *
     * @param RequestInterface $psrHttpMessageRequest
     *
     * @return Request
     */
    public function createRequest(RequestInterface $psrHttpMessageRequest);

    /**
     * Creates a Symfony Response from a PSR-7 one.
     *
     * @param ResponseInterface $psrHttpMessageResponse
     *
     * @return Response
     */
    public function createResponse(ResponseInterface $psrHttpMessageResponse);
}
