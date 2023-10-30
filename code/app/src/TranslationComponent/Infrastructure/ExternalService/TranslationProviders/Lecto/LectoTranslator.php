<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\ExternalService\TranslationProviders\Lecto;

use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;
use App\TranslationComponent\Infrastructure\ExternalService\TranslationProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\TranslationComponent\Infrastructure\HttpClient\Client;
use Symfony\Component\HttpClient\Exception\TransportException;

class LectoTranslator implements TranslationProviderInterface
{
    public const API_URL = 'https://api.lecto.ai/v1/';
    private const SUCCESSFUL_RESPONSE_CODE = 200;

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly Client $client,
        private readonly LoggerInterface $logger
    ) {
    }

    public function translate(string $sourceText, string $sourceLanguage, string $targetLanguage): ?TranslationValueObject
    {
        $headers = [
            'Accept: application/json',
            'X-API-Key: ' . $this->container->getParameter('lecto_api_key'),
        ];

        $body = [
            'texts' => [$sourceText],
            'from' => $sourceLanguage,
            'to' => [$targetLanguage],
        ];

        $url = self::API_URL . 'translate/text';

        try {
            $response = $this->client->postRequest(
                headers: $headers,
                url: $url,
                body: $body,
                bodyFormat: 'json'
            );
        } catch (TransportException $e) {
            $this->logger->error('Lecto API is not available');
            return null;
        }

        if ($response->getStatusCode() !== self::SUCCESSFUL_RESPONSE_CODE) {
            $this->logger->error('Lecto API returned error code: ' . $response->getStatusCode() . ' - ' . $response->getContent(throw:false));
            return null;
        }

        $result = json_decode($response->getContent(throw: false), true);

        $this->logger->info('Lecto API translation result: ' . $result['data'][0]['translations'][0]['text']);

        return new TranslationValueObject(
            sourceText: $sourceText,
            sourceLanguage: $sourceLanguage,
            targetLanguage: $targetLanguage,
            translatedText: $result['translations'][0]['text'],
        );
    }
}
