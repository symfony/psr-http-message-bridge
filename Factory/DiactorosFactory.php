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

use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Zend\Diactoros\Response as DiactorosResponse;
use Zend\Diactoros\ServerRequestFactory as DiactorosRequestFactory;
use Zend\Diactoros\Stream as DiactorosStream;
use Zend\Diactoros\UploadedFile as DiactorosUploadedFile;

/**
 * Builds Psr\HttpMessage instances using the Zend Diactoros implementation.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DiactorosFactory implements HttpMessageFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createRequest(Request $symfonyRequest)
    {
        $request = DiactorosRequestFactory::fromGlobals(
            $symfonyRequest->server->all(),
            $symfonyRequest->query->all(),
            $symfonyRequest->request->all(),
            $symfonyRequest->cookies->all(),
            $this->getFiles($symfonyRequest->files->all())
        );

        try {
            $stream = new DiactorosStream($symfonyRequest->getContent(true));
        } catch (\LogicException $e) {
            $stream = new DiactorosStream('php://temp', 'wb+');
            $stream->write($symfonyRequest->getContent());
        }

        $request = $request->withBody($stream);

        foreach ($symfonyRequest->attributes->all() as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        return $request;
    }

    /**
     * Converts Symfony uploaded files array to the PSR one.
     *
     * @param array $uploadedFiles
     *
     * @return array
     */
    private function getFiles(array $uploadedFiles)
    {
        $files = array();

        foreach ($uploadedFiles as $key => $value) {
            if ($value instanceof UploadedFile) {
                $files[$key] = $this->createUploadedFile($value);
            } else {
                $files[$key] = $this->getFiles($value);
            }
        }

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function createUploadedFile(UploadedFile $symfonyUploadedFile)
    {
        return new DiactorosUploadedFile(
            $symfonyUploadedFile->getRealPath(),
            $symfonyUploadedFile->getSize(),
            $symfonyUploadedFile->getError(),
            $symfonyUploadedFile->getClientOriginalName(),
            $symfonyUploadedFile->getClientMimeType()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(Response $symfonyResponse)
    {
        $stream = new DiactorosStream('php://temp', 'wb+');
        if ($symfonyResponse instanceof StreamedResponse) {
            ob_start(function ($buffer) use ($stream) {
                $stream->write($buffer);

                return false;
            });
            $symfonyResponse->sendContent();
            ob_end_clean();
        } else {
            $stream->write($symfonyResponse->getContent());
        }

        $response = new DiactorosResponse(
            $stream,
            $symfonyResponse->getStatusCode(),
            $symfonyResponse->headers->all()
        );

        $protocolVersion = $symfonyResponse->getProtocolVersion();
        if ('1.1' !== $protocolVersion) {
            $response = $response->withProtocolVersion($protocolVersion);
        }

        return $response;
    }
}
