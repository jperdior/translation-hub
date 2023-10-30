<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\UseCase;

use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;
use App\TranslationComponent\Domain\Entity\Translation;

class SaveQueuedTranslationUseCase
{
    public function __construct(
        private readonly TranslationRepositoryInterface $translationRepository
    ) {
    }

    public function execute(string $sourceText, string $sourceLanguage, string $targetLanguage): Translation
    {
        $translation = new Translation();
        $translation->setSourceText(sourceText: $sourceText);
        $translation->setSourceLanguage(sourceLanguage: $sourceLanguage);
        $translation->setTargetLanguage(targetLanguage: $targetLanguage);

        $this->translationRepository->save(
            translation: $translation
        );

        return $translation;
    }
}
