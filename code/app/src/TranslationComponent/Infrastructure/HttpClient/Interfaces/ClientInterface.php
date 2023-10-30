<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\HttpClient\Interfaces;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ClientInterface
{
    public function postRequest(array $headers, string $url, array $body, string $bodyFormat): ResponseInterface | array;
}
