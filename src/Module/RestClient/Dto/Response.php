<?php

namespace GorillaSoft\Grimlock\Module\RestClient\Dto;

use GorillaSoft\Grimlock\Core\Collection\Collection;
use GorillaSoft\Grimlock\Core\Exception\CoreException;
use Psr\Http\Message\ResponseInterface;

class Response
{

    public Collection $headers {
        get {
            return $this->headers;
        }
        set {
            $this->headers = $value;
        }
    }

    public int $code {
        get {
            return $this->code;
        }
        set {
            $this->code = $value;
        }
    }

    public string $body {
        get {
            return $this->body;
        }
        set {
            $this->body = $value;
        }
    }

    public static function create(ResponseInterface $response): Response
    {
        $code = $response->getStatusCode();
        $headers = new Collection();
        foreach ($response->getHeaders() as $name => $values) {
            $grimlockHeader = new Header();
            $grimlockHeader->name = $name;
            $grimlockHeader->value = $values[0];
            $headers->append($grimlockHeader);
        }
        $body = $response->getBody()->getContents();

        return new Response($code, $headers, $body);
    }

    private function __construct(int $code, Collection $headers, string $body)
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function processResponse(ResponseInterface $response): void
    {
        $this->code = $response->getStatusCode();
        foreach ($response->getHeaders() as $name => $values) {
            $grimlockHeader = new Header();
            $grimlockHeader->name = $name;
            $grimlockHeader->value = $values[0];
            $this->headers->append($grimlockHeader);
        }
        $this->body = $response->getBody()->getContents();
    }

    /**
     * @throws CoreException
     */
    public function getHeader(string $name): ?Header
    {
        for ($i = 0; $i < $this->headers->size(); $i ++)
        {
            $header = $this->headers->get($i);
            if ($header->getName() === $name) {
                return $header;
            }
        }
        return null;
    }

}
