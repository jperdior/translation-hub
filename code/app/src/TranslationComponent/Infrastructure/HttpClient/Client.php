<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\HttpClient;

use App\TranslationComponent\Infrastructure\HttpClient\Interfaces\ClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client implements ClientInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ClientHelper $clientHelper,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getRequest(array $headers, string $url)
    {
        $options = [
            'headers' => $headers,
        ];

        return $this->client->request(
            method: 'GET',
            url: $url,
            options: $options
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function postRequest(array $headers, string $url, array $body, string $bodyFormat): ResponseInterface | array
    {
        $body = $this->clientHelper->prepareBody($body, $bodyFormat);

        $options = [
            'headers' => $headers,
            'body' => $body,
        ];

        $result =  $this->client->request(
            method: 'POST',
            url: $url,
            options: $options
        );

        return $result;
    }
}
