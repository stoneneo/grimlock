<?php

namespace GorillaSoft\Grimlock\Module\RestClient;

use GorillaSoft\Grimlock\Module\RestClient\Dto\Response;

interface RestClientInterface
{
    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addHeader(string $key, string $value): void;

    /**
     * @param string $uri
     * @param array<string, string> $query
     * @return Response
     */
    public function get(string $uri, array $query = []): Response;

    /**
     * @param string $uri
     * @param array<string, mixed> $body
     * @return Response
     */
    public function post(string $uri, array $body): Response;

    /**
     * @param string $uri
     * @param array<string, mixed> $body
     * @return Response
     */
    public function put(string $uri, array $body): Response;

    /**
     * @param string $uri
     * @param array<string, mixed> $body
     * @return Response
     */
    public function patch(string $uri, array $body): Response;

    /**
     * @param string $uri
     * @param array<string, string> $query
     * @return Response
     */
    public function delete(string $uri, array $query = []): Response;
}
