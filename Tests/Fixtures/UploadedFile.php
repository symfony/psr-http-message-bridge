<?php

namespace Symfony\Bridge\PsrHttpMessage\Tests\Fixtures;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class UploadedFile implements UploadedFileInterface
{
    private $filePath;
    private $size;
    private $error;
    private $clientFileName;
    private $clientMediaType;

    public function __construct($filePath, $size = null, $error = UPLOAD_ERR_OK, $clientFileName = null, $clientMediaType = null)
    {
        $this->filePath = $filePath;
        $this->size = $size;
        $this->error = $error;
        $this->clientFileName = $clientFileName;
        $this->clientMediaType = $clientMediaType;
    }

    public function getStream()
    {
        throw new \RuntimeException('No stream is available.');
    }

    public function moveTo($targetPath)
    {
        rename($this->filePath, $targetPath);
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getClientFilename()
    {
        return $this->clientFileName;
    }

    public function getClientMediaType()
    {
        return $this->clientMediaType;
    }
}
