<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\Form;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\RequestHandlerInterface;

/**
 * Class Psr7RequestHandler.
 */
class Psr7RequestHandler implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(FormInterface $form, $request = null): void
    {
        if (!$request instanceof Request) {
            throw new UnexpectedTypeException($request, Request::class);
        }

        $name = $form->getName();
        $method = $form->getConfig()->getMethod();

        if ($method !== $request->getMethod()) {
            return;
        }

        // For request methods that must not have a request body we fetch data
        // from the query string. Otherwise we look for data in the request body.
        if ($method === 'GET' || $method === 'HEAD' || $method === 'TRACE') {
            if ($name === '') {
                $data = $request->getQueryParams();
            } else {
                // Don't submit GET requests if the form's name does not exist
                // in the request
                $data = $request->getQueryParams()[$name] ?? null;
                if ($data === null) {
                    return;
                }
            }
        } else {
            // Mark the form with an error if the uploaded size was too large
            // This is done here and not in FormValidator because $_POST is
            // empty when that error occurs. Hence the form is never submitted.
            if ($this->hasPostMaxSizeBeenExceeded($request)) {
                // Submit the form, but don't clear the default values
                $form->submit(null, false);

                $form->addError(new FormError(
                    $form->getConfig()->getOption('upload_max_size_message')(),
                    null,
                    ['{{ max }}' => $this->getNormalizedIniPostMaxSize()]
                ));

                return;
            }

            if ($name === '') {
                $params = $request->getParsedBody();
                $files = $request->getUploadedFiles();
            } elseif (isset($request->getParsedBody()[$name]) || isset($request->getUploadedFiles()[$name])) {
                $default = $form->getConfig()->getCompound() ? [] : null;
                $params = $request->getParsedBody()[$name] ?? $default;
                $files = $request->getUploadedFiles()[$name] ?? $default;
            } else {
                // Don't submit the form if it is not present in the request
                return;
            }

            if (\is_array($params) && \is_array($files)) {
                $data = array_replace_recursive($params, $files);
            } else {
                $data = $params ?: $files;
            }
        }

        // Don't auto-submit the form unless at least one field is present.
        if ($name === '' && \count(array_intersect_key($data, $form->all())) <= 0) {
            return;
        }

        $form->submit($data, $method !== 'PATCH');
    }

    /**
     * {@inheritdoc}
     */
    public function isFileUpload($data): bool
    {
        return $data instanceof UploadedFileInterface;
    }

    /**
     * @param $data
     */
    public function getUploadFileError($data): ?int
    {
        if (!$data instanceof UploadedFileInterface || $data->getError() === UPLOAD_ERR_OK) {
            return null;
        }

        return $data->getError();
    }

    /**
     * Returns true if the POST max size has been exceeded in the request.
     */
    private function hasPostMaxSizeBeenExceeded(Request $request): bool
    {
        $contentLength = $this->getContentLength($request);
        $maxContentLength = $this->getPostMaxSize();

        return $maxContentLength && $contentLength > $maxContentLength;
    }

    /**
     * Returns maximum post size in bytes.
     *
     * @return int|null The maximum post size in bytes
     */
    private function getPostMaxSize(): ?int
    {
        $iniMax = strtolower($this->getNormalizedIniPostMaxSize());

        if ($iniMax === '') {
            return null;
        }

        $max = ltrim($iniMax, '+');
        if (0 === strpos($max, '0x')) {
            $max = \intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = \intval($max, 8);
        } else {
            $max = (int) $max;
        }

        switch (substr($iniMax, -1)) {
            case 't': $max *= 1024;
            // no break
            case 'g': $max *= 1024;
            // no break
            case 'm': $max *= 1024;
            // no break
            case 'k': $max *= 1024;
        }

        return $max;
    }

    /**
     * Returns the normalized "post_max_size" ini setting.
     */
    private function getNormalizedIniPostMaxSize(): string
    {
        return strtoupper(trim(ini_get('post_max_size')));
    }

    /**
     * Returns the content length of the request.
     *
     * @return mixed The request content length
     */
    private function getContentLength(Request $request)
    {
        if ($request->hasHeader('Content-Length')) {
            return $request->getHeaderLine('Content-Length');
        }

        return isset($_SERVER['CONTENT_LENGTH'])
            ? (int) $_SERVER['CONTENT_LENGTH']
            : null;
    }
}
