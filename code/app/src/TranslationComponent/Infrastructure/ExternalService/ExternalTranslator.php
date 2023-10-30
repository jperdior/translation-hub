<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\ExternalService;

use App\TranslationComponent\Domain\ExternalService\ExternalTranslatorInterface;
use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;
use App\TranslationComponent\Infrastructure\HttpClient\Client;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExternalTranslator implements ExternalTranslatorInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly Client $client,
        private readonly LoggerInterface $logger
    ) {
    }

    public function translate(string $sourceText, string $sourceLanguage, string $targetLanguage): TranslationValueObject
    {
        foreach ($this->container->getParameter('translation_services') as $service) {
            /**
             * @var TranslationProviderInterface $translator
             */
            $translator = new $service($this->container, $this->client, $this->logger);
            $translationValueObject = $translator->translate($sourceText, $sourceLanguage, $targetLanguage);
            if ($translationValueObject !== null && $translationValueObject->translatedText !== '') {
                return $translationValueObject;
            }
        }
        throw new Exception('None of the translation services is available');
    }
}
