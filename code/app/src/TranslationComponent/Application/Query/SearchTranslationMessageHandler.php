<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\Query;

use App\TranslationComponent\Application\DataTransformer\TranslationDataTransformer;
use App\TranslationComponent\Domain\UseCase\SearchStoredTranslationUseCase;
use App\TranslationComponent\Presentation\Swagger\TranslationSwagger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SearchTranslationMessageHandler
{
    public function __construct(
        private readonly SearchStoredTranslationUseCase $searchStoredTranslationUseCase,
        private readonly TranslationDataTransformer     $translationDataTransformer,
        private readonly LoggerInterface                $logger
    ) {
    }

    public function __invoke(SearchTranslationMessage $getTranslationMessage): ?TranslationSwagger
    {
        $this->logger->info('Search stored translation for sourceText: ' . $getTranslationMessage->translationSwagger->sourceText . ' and sourceLanguage: ' .
            $getTranslationMessage->translationSwagger->sourceLanguage . ' and targetLanguage: ' . $getTranslationMessage->translationSwagger->targetLanguage);
        $translation = $this->searchStoredTranslationUseCase->execute(
            sourceText: $getTranslationMessage->translationSwagger->sourceText,
            sourceLanguage: $getTranslationMessage->translationSwagger->sourceLanguage,
            targetLanguage: $getTranslationMessage->translationSwagger->targetLanguage
        );

        if ($translation === null) {
            $this->logger->info('Translation not found');
            return null;
        }

        $this->logger->info('Translation found');
        $this->translationDataTransformer->toSwaggerClass($translation, $getTranslationMessage->translationSwagger);
        return $getTranslationMessage->translationSwagger;
    }
}
