<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\UseCase;

use App\TranslationComponent\Domain\Entity\Translation;
use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;

class SearchStoredTranslationUseCase
{
    public function __construct(
        private readonly TranslationRepositoryInterface $translationRepository
    ) {
    }

    public function execute(string $sourceText, string $sourceLanguage, string $targetLanguage): ?Translation
    {
        return $this->translationRepository->findTranslation(sourceText: $sourceText, sourceLanguage: $sourceLanguage, targetLanguage: $targetLanguage);
    }
}
