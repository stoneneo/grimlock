<?php

namespace GorillaSoft\Grimlock\Tests\Module\RestClient;

use PHPUnit\Framework\TestCase;
use GorillaSoft\Grimlock\Module\RestClient\RestClient;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Client;
use ReflectionProperty;
use Exception;

class RestClientTest extends TestCase
{
    /**
     * @throws CoreException
     */
    public function testGetReturnsGrimlockResponse(): void
    {
        $baseUri = 'https://api.test';
        $client = new RestClient($baseUri, 2, false);
        $client->addHeader('X-Test', 'value');

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('getContents')->willReturn('{"ok":true}');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getHeaders')->willReturn(['Content-Type' => ['application/json']]);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockGuzzle = $this->createMock(Client::class);
        $mockGuzzle->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                $baseUri . '/resource',
                $this->callback(function ($options) {
                    TestCase::assertArrayHasKey('headers', $options);
                    TestCase::assertArrayHasKey('X-Test', $options['headers']);
                    TestCase::assertEquals('value', $options['headers']['X-Test']);
                    TestCase::assertArrayHasKey('query', $options);
                    TestCase::assertEquals(['a' => 'b'], $options['query']);
                    TestCase::assertArrayHasKey('timeout', $options);
                    return true;
                })
            )
            ->willReturn($mockResponse);

        $ref = new ReflectionProperty(RestClient::class, 'client');
        $ref->setAccessible(true);
        $ref->setValue($client, $mockGuzzle);

        $resp = $client->get('/resource', ['a' => 'b']);

        $this->assertEquals(200, $resp->code);
        $this->assertEquals('{"ok":true}', $resp->body);
    }

    /**
     * @throws CoreException
     */
    public function testPostSendsJsonAndReturnsResponse(): void
    {
        $baseUri = 'https://api.test';
        $client = new RestClient($baseUri);

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('getContents')->willReturn('ok');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(201);
        $mockResponse->method('getHeaders')->willReturn([]);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockGuzzle = $this->createMock(Client::class);
        $mockGuzzle->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                $baseUri . '/create',
                $this->callback(function ($options) {
                    TestCase::assertArrayHasKey('headers', $options);
                    TestCase::assertArrayHasKey('json', $options);
                    TestCase::assertEquals(['name' => 'john'], $options['json']);
                    return true;
                })
            )
            ->willReturn($mockResponse);

        $ref = new ReflectionProperty(RestClient::class, 'client');
        $ref->setAccessible(true);
        $ref->setValue($client, $mockGuzzle);

        $resp = $client->post('/create', ['name' => 'john']);

        $this->assertEquals(201, $resp->code);
        $this->assertEquals('ok', $resp->body);
    }

    /**
     * @throws CoreException
     */
    public function testPutSendsJsonAndReturnsResponse(): void
    {
        $baseUri = 'https://api.test';
        $client = new RestClient($baseUri);

        $mockStream = $this->createMock(StreamInterface::class);
        $mockStream->method('getContents')->willReturn('updated');

        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('getStatusCode')->willReturn(200);
        $mockResponse->method('getHeaders')->willReturn([]);
        $mockResponse->method('getBody')->willReturn($mockStream);

        $mockGuzzle = $this->createMock(Client::class);
        $mockGuzzle->expects($this->once())
            ->method('request')
            ->with(
                'PUT',
                $baseUri . '/update',
                $this->callback(function ($options) {
                    TestCase::assertArrayHasKey('headers', $options);
                    TestCase::assertArrayHasKey('json', $options);
                    TestCase::assertEquals(['id' => 1], $options['json']);
                    return true;
                })
            )
            ->willReturn($mockResponse);

        $ref = new ReflectionProperty(RestClient::class, 'client');
        $ref->setAccessible(true);
        $ref->setValue($client, $mockGuzzle);

        $resp = $client->put('/update', ['id' => 1]);

        $this->assertEquals(200, $resp->code);
        $this->assertEquals('updated', $resp->body);
    }

    public function testRequestErrorThrowsGrimlockException(): void
    {
        $this->expectException(CoreException::class);

        $client = new RestClient('https://api.test');

        $mockGuzzle = $this->createMock(Client::class);
        $mockGuzzle->expects($this->once())
            ->method('request')
            ->will($this->throwException(new Exception('network')));

        $ref = new ReflectionProperty(RestClient::class, 'client');
        $ref->setAccessible(true);
        $ref->setValue($client, $mockGuzzle);

        $client->get('/fail');
    }
}
