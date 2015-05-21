<?php

namespace Symfony\Bridge\PsrHttpMessage;

use Psr\Http\Message\ServerRequestInterface;
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
     * @return ServerRequestInterface
     */
    public static function createRequest(Request $symfonyRequest);

    /**
     * Creates a PSR-7 Response from a Symfony one.
     *
     * @param Response $symfonyResponse
     *
     * @return ResponseInterface
     */
    public static function createResponse(Response $symfonyResponse);
}
