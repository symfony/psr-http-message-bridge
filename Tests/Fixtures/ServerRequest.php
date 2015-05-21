<?php

namespace Symfony\Bridge\PsrHttpMessage\Tests\Fixtures;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ServerRequest extends Message implements ServerRequestInterface
{
    private $requestTarget = '/';
    private $method = 'GET';
    private $uri;
    private $server = array();
    private $cookies = array();
    private $query = array();
    private $uploadedFiles = array();
    private $data = null;
    private $attributes = array();

    public function getRequestTarget()
    {
        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget)
    {
        $this->requestTarget = $requestTarget;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $this->method = $method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $this->uri = $uri;
    }

    public function getServerParams()
    {
        return $this->server;
    }

    public function withServerParams(array $serverParams)
    {
        $this->server = $serverParams;
    }

    public function getCookieParams()
    {
        return $this->cookies;
    }

    public function withCookieParams(array $cookies)
    {
        $this->cookies = $cookies;
    }

    public function getQueryParams()
    {
        return $this->query;
    }

    public function withQueryParams(array $query)
    {
        $this->query = $query;
    }

    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;
    }

    public function getParsedBody()
    {
        return $this->data;
    }

    public function withParsedBody($data)
    {
        $this->data = $data;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
    }

    public function withAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function withoutAttribute($name)
    {
        if ($this->getAttribute($name)) {
            unset($this->attributes[$name]);
        }
    }
}
