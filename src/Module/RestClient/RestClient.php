<?php

namespace GorillaSoft\Grimlock\Module\RestClient;

use Exception;
use GorillaSoft\Grimlock\Core\Collection\CollectionList;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use GorillaSoft\Grimlock\Module\RestClient\Dto\Header;
use GorillaSoft\Grimlock\Module\RestClient\Dto\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class RestClient
{

    private string $baseUri;
    private int $timeout;
    private CollectionList $headers;
    private Client $client;

    /**
     * @param string $baseUri
     * @param int $timeout
     * @param bool $sslEnabled
     */
    public function __construct(string $baseUri, int $timeout = 2, bool $sslEnabled = true)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => $timeout,
            'verify' => $sslEnabled,
        ]);
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
        $this->headers = new CollectionList();
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function addHeader($name, $value): void
    {
        $header = new Header();
        $header->name = $name;
        $header->value = $value;
        $this->headers->append($header);
    }

    /**
     * @throws CoreException
     */
    private function getHeaders(): array {
        $headers = array();
        for ($i = 0; $i < $this->headers->getSize(); $i++)
        {
            $item = $this->headers->getItem($i);
            $headers[$item->name] = $item->value;
        }

        return $headers;
    }

    /**
     * @throws CoreException
     */
    public function get(string $uri, array $query = array()): Response
    {
        try {
            $response = $this->client->request('GET', $this->baseUri . $uri, [
                'headers' => $this->getHeaders(),
                'query' => $query,
                'timeout' => $this->timeout
            ]);
            return Response::create($response);
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        } catch (GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @throws CoreException
     */
    public function post(string $uri, array $body): Response
    {
        try {
            $response = $this->client->request('POST', $this->baseUri . $uri, [
               'headers' => $this->getHeaders(),
               'json' => $body
            ]);
            return Response::create($response);
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        } catch (GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

    /**
     * @throws CoreException
     */
    public function put(string $uri, array $body): Response
    {
        try {
            $response = $this->client->request('PUT', $this->baseUri . $uri, [
                'headers' => $this->getHeaders(),
                'json' => $body
            ]);
            return Response::create($response);
        } catch (Exception $e) {
            throw new CoreException(self::class, $e->getMessage());
        } catch (GuzzleException $e) {
            throw new CoreException(self::class, $e->getMessage());
        }
    }

}
