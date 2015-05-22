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
        $stdClass = new \stdClass();
        $serverRequest = new ServerRequest(
            '1.1',
            array(),
            new Stream('The body'),
            '/about/kevin',
            'GET',
            'http://les-tilleuls.coop/about/kevin',
            array('country' => 'France'),
            array('city' => 'Lille'),
            array('url' => 'http://les-tilleuls.coop'),
            array(),
            array('url' => 'http://dunglas.fr'),
            array('custom' => $stdClass)
        );

        $symfonyRequest = $this->factory->createRequest($serverRequest);

        $this->assertEquals('http://les-tilleuls.coop', $symfonyRequest->query->get('url'));
        $this->assertEquals('http://dunglas.fr', $symfonyRequest->request->get('url'));
        $this->assertEquals($stdClass, $symfonyRequest->attributes->get('custom'));
        $this->assertEquals('Lille', $symfonyRequest->cookies->get('city'));
        $this->assertEquals('France', $symfonyRequest->server->get('country'));
        $this->assertEquals('The body', $symfonyRequest->getContent());
    }

    public function testCreateRequestWithNullParsedBody()
    {
        $serverRequest = new ServerRequest(
            '1.1',
            array(),
            new Stream(),
            '/',
            'GET',
            null,
            array(),
            array(),
            array(),
            array(),
            null,
            array()
        );

        $this->assertCount(0, $this->factory->createRequest($serverRequest)->request);
    }

    public function testCreateRequestWithObjectParsedBody()
    {
        $serverRequest = new ServerRequest(
            '1.1',
            array(),
            new Stream(),
            '/',
            'GET',
            null,
            array(),
            array(),
            array(),
            array(),
            new \stdClass(),
            array()
        );

        $this->assertCount(0, $this->factory->createRequest($serverRequest)->request);
    }

    public function testCreateResponse()
    {

    }
}
