<?php

namespace Symfony\Bridge\PsrHttpMessage;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Creates Symfony Request and Response instances from PSR-7 ones.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface HttpFoundationFactoryInterface
{
    /**
     * Creates a Symfony Request instance from a PSR-7 one.
     *
     * @param ServerRequestInterface $psrRequest
     *
     * @return Request
     */
    public function createRequest(ServerRequestInterface $psrRequest);

    /**
     * Creates Symfony UploadedFile instance from PSR-7 ones.
     *
     * @param UploadedFileInterface $psrUploadedFile
     *
     * @return UploadedFile
     */
    public function createUploadedFile(UploadedFileInterface $psrUploadedFile);

    /**
     * Creates a Symfony Response instance from a PSR-7 one.
     *
     * @param ResponseInterface $psrResponse
     *
     * @return Response
     */
    public function createResponse(ResponseInterface $psrResponse);
}
