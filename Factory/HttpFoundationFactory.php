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
    public function createRequest(ServerRequestInterface $psrHttpMessageRequest)
    {
        $parsedBody = $psrHttpMessageRequest->getParsedBody();
        $request = is_array($parsedBody) ? $parsedBody : [];

        return new Request(
            $psrHttpMessageRequest->getQueryParams(),
            $request,
            $psrHttpMessageRequest->getAttributes(),
            $psrHttpMessageRequest->getCookieParams(),
            array(), // TODO
            $psrHttpMessageRequest->getServerParams(),
            $psrHttpMessageRequest->getBody()->__toString()
        );
    }

    private function getFiles(array $uploadedFiles)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(ResponseInterface $psrHttpMessageResponse)
    {
        return new Response(

        );
    }
}
