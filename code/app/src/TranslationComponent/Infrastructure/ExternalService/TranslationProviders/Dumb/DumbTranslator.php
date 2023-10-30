<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\ExternalService\TranslationProviders\Dumb;

use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;
use App\TranslationComponent\Infrastructure\ExternalService\TranslationProviderInterface;
use App\TranslationComponent\Infrastructure\HttpClient\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DumbTranslator implements TranslationProviderInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly Client $client,
        private readonly LoggerInterface $logger
    ) {

    }

    public function translate(string $sourceText, string $sourceLanguage, string $targetLanguage): ?TranslationValueObject
    {

        $this->logger->info('Dumb translator "translated" this :(');

        return new TranslationValueObject(
            sourceText: $sourceText,
            sourceLanguage: $sourceLanguage,
            targetLanguage: $targetLanguage,
            translatedText: $sourceText . ' translated to ' . $targetLanguage
        );
    }
}
