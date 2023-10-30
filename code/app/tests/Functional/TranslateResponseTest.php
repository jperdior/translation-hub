<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;

/**
 * @group Functional
 */
class TranslateResponseTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    public function testTranslateFromInvalidLanguage(): void
    {
        //dump(getenv('APP_ENV'));die;

        $client = static::createClient();

        $translation = [
            'sourceLanguage' => 'frrr',
            'sourceText' => 'test',
            'targetLanguage' => 'EN-GB',
        ];

        $client->request(method: 'POST', url: '/api/translate', options: ['json' => $translation]);

        $this->assertResponseStatusCodeSame(expectedCode: 422);

        $this->assertJsonContains(subset: [
            'hydra:description' => 'sourceLanguage: The value "'. $translation['sourceLanguage'] .'" is not a valid language.',
        ]);
    }

    public function testTranslateToInvalidLanguage(): void
    {
        $client = static::createClient();

        $translation = [
            'sourceLanguage' => 'EN',
            'sourceText' => 'test',
            'targetLanguage' => 'invalid',
        ];

        $client->request(method: 'POST', url: '/api/translate', options: ['json' => $translation]);

        $this->assertResponseStatusCodeSame(expectedCode: 422);

        $this->assertJsonContains(subset: [
            'hydra:description' => 'targetLanguage: The value "'. $translation['targetLanguage'] .'" is not a valid language.',
        ]);
    }

    public function testTranslateTextTooLong(): void
    {
        $client = static::createClient();

        $translation = [
            'sourceLanguage' => 'EN',
            'sourceText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sed magna ipsum. Aenean nec aliquam dolor. Nullam vel scelerisque diam, quis condimentum odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec gravida, odio id aliquam pulvinar, erat elit porta nulla, quis viverra sapien leo ut lacus. Nam venenatis sem ac nibh aliquam lobortis.',
            'targetLanguage' => 'EN-GB',
        ];

        $client->request(method: 'POST', url: '/api/translate', options: ['json' => $translation]);

        $this->assertResponseStatusCodeSame(expectedCode: 422);

        $this->assertJsonContains(subset: [
            'hydra:description' => 'sourceText: Text to translate cannot be longer than 255 characters',
        ]);
    }

    public function testTranslateTextEmpty(): void
    {
        $client = static::createClient();

        $translation = [
            'sourceLanguage' => 'EN',
            'targetLanguage' => 'EN-GB',
        ];

        $client->request(method: 'POST', url: '/api/translate', options: ['json' => $translation]);

        $this->assertResponseStatusCodeSame(expectedCode: 422);

        $this->assertJsonContains(subset: [
            'hydra:description' => 'sourceText: This value should not be null.',
        ]);
    }

    public function testTranslateTextNotExisting(): void
    {
        $client = static::createClient();

        $client->request(method: 'POST', url: '/api/translate', options: ['json' => MockTranslationResponse::NOT_EXISTING_BODY]);

        $json = json_decode(MockTranslationResponse::getNotExistingTranslation(), true);

        $this->assertJsonEquals(json: $json);

        $this->assertResponseStatusCodeSame(expectedCode: 201);
    }

    public function testTranslateTextExisting(): void
    {
        $client = static::createClient();

        $client->request(method: 'POST', url: '/api/translate', options: ['json' => MockTranslationResponse::EXISTING_BODY]);

        $json = json_decode(MockTranslationResponse::getExistingTranslation(), true);

        $this->assertJsonEquals(json: $json);

        $this->assertResponseStatusCodeSame(expectedCode: 201);
    }
}
