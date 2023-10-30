<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\ExternalService\TranslationProviders\DeepL;

use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;
use App\TranslationComponent\Infrastructure\ExternalService\TranslationProviderInterface;
use App\TranslationComponent\Infrastructure\HttpClient\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\Exception\TransportException;

class DeepLTranslator implements TranslationProviderInterface
{
    public const API_URL = 'https://api-free.deepl.com/v2/';
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
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'Authorization: ' . $this->container->getParameter('deepl_api_key'),
        ];

        $body = [
            'text' => $sourceText,
            'source_lang' => $sourceLanguage,
            'target_lang' => $targetLanguage,
        ];

        $url = self::API_URL . 'translate';

        try {
            $response = $this->client->postRequest(
                headers: $headers,
                url: $url,
                body: $body,
                bodyFormat: 'x-www-form-urlencoded'
            );
        } catch (TransportException $e) {
            $this->logger->error('DeepL API is not available');
            return null;
        }

        if ($response->getStatusCode() !== self::SUCCESSFUL_RESPONSE_CODE) {
            $this->logger->error('DeepL API returned error code: ' . $response->getStatusCode() . ' - ' . $response->getContent(throw:false));
            return null;
        }

        $result = json_decode($response->getContent(throw: false), true);

        $this->logger->info('DeepL API translation result: ' . $result['translations'][0]['text']);

        return new TranslationValueObject(
            sourceText: $sourceText,
            sourceLanguage: $sourceLanguage,
            targetLanguage: $targetLanguage,
            translatedText: $result['translations'][0]['text'],
        );
    }
}
