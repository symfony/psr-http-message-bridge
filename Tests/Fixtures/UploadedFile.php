<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    public function __construct(string $filePath, int $size = null, int $error = \UPLOAD_ERR_OK, string $clientFileName = null, string $clientMediaType = null)
    {
        $this->filePath = $filePath;
        $this->size = $size;
        $this->error = $error;
        $this->clientFileName = $clientFileName;
        $this->clientMediaType = $clientMediaType;
    }

    public function getStream(): StreamInterface
    {
        return new Stream(file_get_contents($this->filePath));
    }

    public function moveTo($targetPath): void
    {
        rename($this->filePath, $targetPath);
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFileName;
    }

    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
