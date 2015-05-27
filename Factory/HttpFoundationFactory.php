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
    public function createRequest(ServerRequestInterface $psrRequest)
    {
        $parsedBody = $psrRequest->getParsedBody();
        $request = is_array($parsedBody) ? $parsedBody : array();

        return new Request(
            $psrRequest->getQueryParams(),
            $request,
            $psrRequest->getAttributes(),
            $psrRequest->getCookieParams(),
            $this->getFiles($psrRequest->getUploadedFiles()),
            $psrRequest->getServerParams(),
            $psrRequest->getBody()->__toString()
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
     * Creates Symfony UploadedFile instance from PSR-7 ones.
     *
     * @param UploadedFileInterface $psrUploadedFile
     *
     * @return UploadedFile
     */
    private function createUploadedFile(UploadedFileInterface $psrUploadedFile)
    {
        $temporaryPath = $this->getTemporaryPath();
        $psrUploadedFile->moveTo($temporaryPath);

        $clientFileName = $psrUploadedFile->getClientFilename();

        return new UploadedFile(
            $temporaryPath,
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
    public function createResponse(ResponseInterface $psrResponse)
    {
        $response = new Response(
            $psrResponse->getBody()->__toString(),
            $psrResponse->getStatusCode(),
            $psrResponse->getHeaders()
        );
        $response->setProtocolVersion($psrResponse->getProtocolVersion());

        return $response;
    }
}
