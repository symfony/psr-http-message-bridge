<?php

namespace Symfony\Bridge\PsrHttpMessage\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $request = is_array($parsedBody) ? $parsedBody : array();

        return new Request(
            $psrHttpMessageRequest->getQueryParams(),
            $request,
            $psrHttpMessageRequest->getAttributes(),
            $psrHttpMessageRequest->getCookieParams(),
            $this->getFiles($psrHttpMessageRequest->getUploadedFiles()),
            $psrHttpMessageRequest->getServerParams(),
            $psrHttpMessageRequest->getBody()->__toString()
        );
    }

    /**
     * Converts to the input array to $_FILES structure.
     *
     * @param array $uploadedFiles
     *
     * @return array
     */
    private function getFiles(array $uploadedFiles)
    {
        $files = array();

        foreach ($uploadedFiles as $key => $value) {
            if ($value instanceof UploadedFileInterface) {
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
    public function createUploadedFile(UploadedFileInterface $psrUploadedFile)
    {
        $tempnam = $this->getTemporaryPath();
        $psrUploadedFile->moveTo($tempnam);

        $clientFileName = $psrUploadedFile->getClientFilename();

        return new UploadedFile(
            $tempnam,
            null === $clientFileName ? '' : $clientFileName,
            $psrUploadedFile->getClientMediaType(),
            $psrUploadedFile->getSize(),
            $psrUploadedFile->getError(),
            true
        );
    }

    /**
     * Gets a temporary file path.
     *
     * @return string
     */
    protected function getTemporaryPath()
    {
        return tempnam(sys_get_temp_dir(), uniqid('symfony', true));
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
