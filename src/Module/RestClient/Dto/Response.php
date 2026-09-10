<?php

namespace GorillaSoft\Grimlock\Module\RestClient\Dto;

use GorillaSoft\Grimlock\Core\Collection\StringMap;
use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @param int $code
     * @param string $body
     * @param StringMap<string> $headers
     */
    public function __construct(
        public int $code,
        public string $body,
        public StringMap $headers,
    ) {
    }

    /**
     * @param ResponseInterface $response
     * @return Response
     */
    public static function create(ResponseInterface $response): Response
    {
        $code = $response->getStatusCode();
        $headers = new StringMap();
        foreach ($response->getHeaders() as $name => $values) {
            $headers->put($name, $values[0]);
        }
        $body = $response->getBody()->getContents();

        return new Response($code, $body, $headers);
    }

}
