<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MockTranslationResponse
{
    public const EXISTING_TRANSLATION = [
        'sourceLanguage' => 'EN',
        'sourceText' => 'I am your father',
        'targetLanguage' => 'DE',
        'translatedText' => 'Ich bin dein Vater',
        'status' => 'Translated'
    ];

    public const EXISTING_BODY = [
        'sourceLanguage' => 'EN',
        'sourceText' => 'I am your father',
        'targetLanguage' => 'DE',
    ];

    public const NOT_EXISTING_TRANSLATION = [
        'sourceLanguage' => 'EN',
        'sourceText' => 'I\'ll be back',
        'targetLanguage' => 'DE',
        'translatedText' => '',
        'status' => 'Queued'
    ];

    public const NOT_EXISTING_BODY = [
        'sourceLanguage' => 'EN',
        'sourceText' => 'I\'ll be back',
        'targetLanguage' => 'DE',
    ];


    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        $body = $options['body'] ?? '';

        $data = match ([$method, $url, $body]) {
            ['POST', '/api/translate', json_encode(self::EXISTING_BODY)] => self::EXISTING_TRANSLATION,
            ['POST', '/api/translate', json_encode(self::NOT_EXISTING_BODY)] => self::NOT_EXISTING_TRANSLATION,
            default => throw new \Exception('Invalid request'),
        };

        return new MockResponse(json_encode($data));
    }

    public static function getNotExistingTranslation(): string
    {
        return '{
          "@context": "/api/contexts/TranslationSwagger",
          "@id": "/api/translation-swaggers/2",
          "@type": "TranslationSwagger",
            "sourceLanguage": "' . self::NOT_EXISTING_TRANSLATION['sourceLanguage'] . '",
            "sourceText": "' . self::NOT_EXISTING_TRANSLATION['sourceText'] . '",
            "targetLanguage": "' . self::NOT_EXISTING_TRANSLATION['targetLanguage'] . '",
            "translatedText": "' . self::NOT_EXISTING_TRANSLATION['translatedText'] . '",
            "status": "' . self::NOT_EXISTING_TRANSLATION['status'] . '"
        }';
    }

    public static function getExistingTranslation(): string
    {
        return '{
          "@context": "/api/contexts/TranslationSwagger",
          "@id": "/api/translation-swaggers/1",
          "@type": "TranslationSwagger",
            "sourceLanguage": "' . self::EXISTING_TRANSLATION['sourceLanguage'] . '",
            "sourceText": "' . self::EXISTING_TRANSLATION['sourceText'] . '",
            "targetLanguage": "' . self::EXISTING_TRANSLATION['targetLanguage'] . '",
            "translatedText": "' . self::EXISTING_TRANSLATION['translatedText'] . '",
            "status": "' . self::EXISTING_TRANSLATION['status'] . '"
        }';
    }

}
