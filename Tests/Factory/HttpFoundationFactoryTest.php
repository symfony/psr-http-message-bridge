<?php

namespace Symfony\Bridge\PsrHttpMessage\Tests\Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\ServerRequest;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\Stream;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class HttpFoundationFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    public function setup()
    {
        $this->factory = new HttpFoundationFactory();
    }

    public function testCreateRequest()
    {
        $serverRequest = new ServerRequest(array('country' => 'France'));
        $serverRequest->withQueryParams(array('url' => 'http://les-tilleuls.coop'));
        $serverRequest->withParsedBody(array('url' => 'http://dunglas.fr'));

        $stdClass = new \stdClass();
        $serverRequest->withAttribute('custom', $stdClass);

        $serverRequest->withCookieParams(array('city' => 'Lille'));
        $serverRequest->withBody(new Stream('The body'));

        $symfonyRequest = $this->factory->createRequest($serverRequest);

        $this->assertEquals('http://les-tilleuls.coop', $symfonyRequest->query->get('url'));
        $this->assertEquals('http://dunglas.fr', $symfonyRequest->request->get('url'));
        $this->assertEquals($stdClass, $symfonyRequest->attributes->get('custom'));
        $this->assertEquals('Lille', $symfonyRequest->cookies->get('city'));
        $this->assertEquals('France', $symfonyRequest->server->get('country'));
        $this->assertEquals('The body', $symfonyRequest->getContent());
    }

    public function testCreateRequestWithNonArrayParsedBody()
    {
        $serverRequest = new ServerRequest();

        $serverRequest->withParsedBody(null);
        $this->assertCount(0, $this->factory->createRequest($serverRequest)->request);

        $serverRequest->withParsedBody(new \stdClass());
        $this->assertCount(0, $this->factory->createRequest($serverRequest)->request);
    }

    public function testCreateResponse()
    {

    }
}

