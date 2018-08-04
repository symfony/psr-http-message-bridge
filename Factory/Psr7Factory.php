<?php

declare(strict_types=1);

namespace Symfony\Bridge\PsrHttpMessage\Factory;

use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Converts Symfony requests and responses to PSR-7 objects with help of PSR-17 HTTP factories.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Psr7Factory implements HttpMessageFactoryInterface
{
    private $serverRequestCreator;

    private $responseFactory;

    private $streamFactory;

    public function __construct(
        ServerRequestCreatorInterface $serverRequestCreator,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->serverRequestCreator = $serverRequestCreator;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function createRequest(Request $symfonyRequest)
    {
        $server = $symfonyRequest->server->all();
        if (empty($server['REQUEST_METHOD'])) {
            $server['REQUEST_METHOD'] = $symfonyRequest->getMethod();
        }

        $request = $this->serverRequestCreator->fromArrays(
            $server,
            $symfonyRequest->headers->all(),
            $symfonyRequest->cookies->all(),
            $symfonyRequest->query->all(),
            $symfonyRequest->request->all(),
            $this->getFiles($symfonyRequest->files->all()),
            $symfonyRequest->getContent(true)
        );


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
            if (null === $value) {
                $files[$key] = [
                    'tmp_name' => tempnam(sys_get_temp_dir(), uniqid('file', true)),
                    'size' => 0,
                    'error' => UPLOAD_ERR_NO_FILE,
                    'name' => '',
                    'type' => '',
                ];
            } elseif ($value instanceof UploadedFile) {
                $files[$key] = [
                        'tmp_name' => $value->getRealPath(),
                        'size' => $value->getSize(),
                        'error' => $value->getError(),
                        'name' => $value->getClientOriginalName(),
                        'type' => $value->getClientMimeType(),
                    ];
            } else {
                $files[$key] = $this->getFiles($value);
            }
        }

        return $files;
    }

    public function createResponse(Response $symfonyResponse)
    {
        if ($symfonyResponse instanceof BinaryFileResponse) {
            $stream = $this->streamFactory->createStreamFromFile($symfonyResponse->getFile()->getPathname(), 'r');
        } else {
            $stream = $this->streamFactory->createStream('');
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
        }

        $headers = $symfonyResponse->headers->all();
        $response = $this->responseFactory->createResponse($symfonyResponse->getStatusCode());
        $response = $response->withBody($stream);
        $response = $response->withProtocolVersion($symfonyResponse->getProtocolVersion());
        foreach ($headers as $headerName => $value) {
           $response = $response->withHeader($headerName, $value);
        }

        return $response;

    }

}