<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Bridge\PsrHttpMessage\Tests\Factory;

use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\ServerRequest;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\Stream;
use Symfony\Bridge\PsrHttpMessage\Tests\Fixtures\UploadedFile;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class HttpFoundationFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;
    private $tmpDir;

    public function setup()
    {
        $this->factory = new HttpFoundationFactory();
        $this->tmpDir = sys_get_temp_dir();
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
            array(
                'doc1' => $this->createUploadedFile('Doc 1', UPLOAD_ERR_OK, 'doc1.txt', 'text/plain'),
                'nested' => array(
                    'docs' => array(
                        $this->createUploadedFile('Doc 2', UPLOAD_ERR_OK, 'doc2.txt', 'text/plain'),
                        $this->createUploadedFile('Doc 3', UPLOAD_ERR_OK, 'doc3.txt', 'text/plain'),
                    )
                )
            ),
            array('url' => 'http://dunglas.fr'),
            array('custom' => $stdClass)
        );

        $symfonyRequest = $this->factory->createRequest($serverRequest);

        $this->assertEquals('http://les-tilleuls.coop', $symfonyRequest->query->get('url'));
        $this->assertEquals('doc1.txt', $symfonyRequest->files->get('doc1')->getClientOriginalName());
        $this->assertEquals('doc2.txt', $symfonyRequest->files->get('nested[docs][0]', null, true)->getClientOriginalName());
        $this->assertEquals('doc3.txt', $symfonyRequest->files->get('nested[docs][1]', null, true)->getClientOriginalName());
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

    public function testCreateUploadedFile()
    {
        $uploadedFile = $this->createUploadedFile('An uploaded file.', UPLOAD_ERR_OK, 'myfile.txt', 'text/plain');
        $symfonyUploadedFile = $this->factory->createUploadedFile($uploadedFile);

        $uniqid = uniqid();
        $symfonyUploadedFile->move($this->tmpDir, $uniqid);

        $this->assertEquals($uploadedFile->getSize(), $symfonyUploadedFile->getClientSize());
        $this->assertEquals(UPLOAD_ERR_OK, $symfonyUploadedFile->getError());
        $this->assertEquals('myfile.txt', $symfonyUploadedFile->getClientOriginalName());
        $this->assertEquals('txt', $symfonyUploadedFile->getClientOriginalExtension());
        $this->assertEquals('text/plain', $symfonyUploadedFile->getClientMimeType());
        $this->assertEquals('An uploaded file.', file_get_contents($this->tmpDir.'/'.$uniqid));
    }

    /**
     * @expectedException        \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @expectedExceptionMessage The file "e" could not be written on disk.
     */
    public function testCreateUploadedFileWithError()
    {
        $uploadedFile = $this->createUploadedFile('Error.', UPLOAD_ERR_CANT_WRITE, 'e', 'text/plain');
        $symfonyUploadedFile = $this->factory->createUploadedFile($uploadedFile);

        $this->assertEquals(UPLOAD_ERR_CANT_WRITE, $symfonyUploadedFile->getError());

        $symfonyUploadedFile->move($this->tmpDir, 'shouldFail.txt');
    }

    private function createUploadedFile($content, $error, $clientFileName, $clientMediaType)
    {
        $filePath = tempnam($this->tmpDir, uniqid());
        file_put_contents($filePath, $content);

        return new UploadedFile($filePath, filesize($filePath), $error, $clientFileName, $clientMediaType);
    }

    public function testCreateResponse()
    {

    }
}
