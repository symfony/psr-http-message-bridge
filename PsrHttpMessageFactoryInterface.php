<?php

namespace Symfony\Bridge\PsrHttpMessage;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Creates PSR HTTP Request and Response from Symfony ones.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PsrHttpMessageFactoryInterface
{
    /**
     * Creates a PSR-7 Request from a  Symfony one.
     *
     * @param Request $symfonyRequest
     *
     * @return RequestInterface
     */
    public function createRequest(Request $symfonyRequest);

    /**
     * Creates a PSR-7 Response from a Symfony one.
     *
     * @param Response $symfonyResponse
     *
     * @return ResponseInterface
     */
    public function createResponse(Response $symfonyResponse);
}
