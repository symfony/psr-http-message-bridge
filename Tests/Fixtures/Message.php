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

    public function __construct($version = '1.1', array $headers = array(), StreamInterface $body = null)
    {
        $this->version = $version;
        $this->headers = $headers;
        $this->body = null === $body ? new Stream() : $body;
    }

    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function withProtocolVersion($version)
    {
        throw \BadMethodCallException('Not implemented.');
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
        throw \BadMethodCallException('Not implemented.');
    }

    public function withAddedHeader($name, $value)
    {
        throw \BadMethodCallException('Not implemented.');
    }

    public function withoutHeader($name)
    {
        throw \BadMethodCallException('Not implemented.');
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        throw \BadMethodCallException('Not implemented.');
    }
}
