<?php

namespace Symfony\Bridge\PsrHttpMessage\Tests\Fixtures;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Message.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class Message implements MessageInterface
{
    private $version = '1.1';
    private $headers = array();
    private $body;

    public function __construct()
    {
        $this->body = new Stream();
    }

    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function withProtocolVersion($version)
    {
        $this->version = $version;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name)
    {
        return $this->hasHeader($name) ? $this->headers[$name] : array();
    }

    public function getHeaderLine($name)
    {
        return $this->hasHeader($name) ? join(',', $this->headers[$name]) : '';
    }

    public function withHeader($name, $value)
    {
        if (!is_array($value)) {
            $value = array($value);
        }

        $this->headers[$name] = $value;
    }

    public function withAddedHeader($name, $value)
    {
        $this->headers[$name][] = $value;
    }

    public function withoutHeader($name)
    {
        if ($this->hasHeader($name)) {
            unset($this->headers[$name]);
        }
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        $this->body = $body;
    }
}
