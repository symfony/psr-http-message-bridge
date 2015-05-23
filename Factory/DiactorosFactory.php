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

use Symfony\Bridge\PsrHttpMessage\PsrHttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zend\Diactoros\ServerRequest as DiactorosRequest;
use Zend\Diactoros\Response as DiactorosResponse;
use Zend\Diactoros\UploadedFile as DiactorosUploadedFile;

/**
 * Builds Psr\HttpMessage instances using the Zend Diactoros implementation.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DiactorosFactory implements PsrHttpMessageFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createRequest(Request $symfonyRequest)
    {
        return new DiactorosRequest(
            $symfonyRequest->server->all(),
            array(),
            $symfonyRequest->getUri(),
            $symfonyRequest->getMethod(),
            $symfonyRequest->getContent(),
            $symfonyRequest->server->getHeaders()
        );
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
    public function createResponse(Response $symfonyResponse)
    {
        return new DiactorosResponse(
            $symfonyResponse->getContent(),
            $symfonyResponse->getStatusCode(),
            $symfonyResponse->headers->all()
        );
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
}
