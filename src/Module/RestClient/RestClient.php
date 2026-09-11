<?php

namespace GorillaSoft\Grimlock\Module\RestClient;

use Exception;
use GorillaSoft\Grimlock\Core\Collection\StringMap;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\RestClient\Dto\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class RestClient implements RestClientInterface
{
    private string $baseUri;
    private int $timeout;

    /**
     * @var StringMap<string>
     */
    private StringMap $headers;
    private Client $client;

    /**
     * @param string $baseUri
     * @param int $timeout
     * @param bool $sslEnabled
     */
    public function __construct(string $baseUri, int $timeout = 2, bool $sslEnabled = true)
    {
        $this->baseUri = rtrim($baseUri, '/') . '/';

        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'timeout' => $timeout,
            'verify' => $sslEnabled,
        ]);
        $this->timeout = $timeout;
        $this->headers = new StringMap();
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addHeader(string $key, string $value): void
    {
        $this->headers->put($key, $value);
    }

    /**
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
        return $this->headers->toArray();
    }


    /**
     * @param string $uri
     * @param array<string, string> $query
     * @return Response
     * @throws CoreException
     */
    public function get(string $uri, array $query = []): Response
    {
        try {
            $response = $this->client->request('GET', ltrim($uri, '/'), [
                'headers' => $this->getHeaders(),
                'query' => $query,
                'timeout' => $this->timeout
            ]);
            return Response::create($response);
        } catch (Exception|GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $uri
     * @param array<string, mixed> $body
     * @return Response
     * @throws CoreException
     */
    public function post(string $uri, array $body): Response
    {
        try {
            $response = $this->client->request('POST', ltrim($uri, '/'), [
               'headers' => $this->getHeaders(),
               'json' => $body,
               'timeout' => $this->timeout
            ]);
            return Response::create($response);
        } catch (Exception|GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }


    /**
     * @param string $uri
     * @param array<string, mixed> $body
     * @return Response
     * @throws CoreException
     */
    public function put(string $uri, array $body): Response
    {
        try {
            $response = $this->client->request('PUT', ltrim($uri, '/'), [
                'headers' => $this->getHeaders(),
                'json' => $body,
                'timeout' => $this->timeout
            ]);
            return Response::create($response);
        } catch (Exception|GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $uri
     * @param array<string, mixed> $body
     * @return Response
     * @throws CoreException
     */
    public function patch(string $uri, array $body): Response
    {
        try {
            $response = $this->client->request('PATCH', ltrim($uri, '/'), [
                'headers' => $this->getHeaders(),
                'json' => $body,
                'timeout' => $this->timeout
            ]);
            return Response::create($response);
        } catch (Exception|GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @param string $uri
     * @param array<string, string> $query
     * @return Response
     * @throws CoreException
     */
    public function delete(string $uri, array $query = []): Response
    {
        try {
            $response = $this->client->request('DELETE', ltrim($uri, '/'), [
                'headers' => $this->getHeaders(),
                'query' => $query,
                'timeout' => $this->timeout
            ]);
            return Response::create($response);
        } catch (Exception|GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

}
