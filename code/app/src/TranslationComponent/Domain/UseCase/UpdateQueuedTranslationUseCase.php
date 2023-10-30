<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\UseCase;

use App\TranslationComponent\Domain\ExternalService\ExternalTranslatorInterface;
use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;
use App\TranslationComponent\Domain\Entity\Translation;
use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;

class UpdateQueuedTranslationUseCase
{
    public function __construct(
        private readonly TranslationRepositoryInterface $translationRepository
    ) {
    }

    public function execute(Translation $translation, string  $translatedText): void
    {
        $translation->setTranslatedText(translatedText: $translatedText);
        $translation->setStatus(Translation::STATUS_TRANSLATED);
        $this->translationRepository->save(translation: $translation);
    }
}
