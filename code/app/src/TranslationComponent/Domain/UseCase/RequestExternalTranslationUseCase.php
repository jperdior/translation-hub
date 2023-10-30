<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\UseCase;

use App\TranslationComponent\Domain\ExternalService\ExternalTranslatorInterface;
use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;
use App\TranslationComponent\Domain\Entity\Translation;
use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;

class RequestExternalTranslationUseCase
{
    public function __construct(
        private readonly ExternalTranslatorInterface $externalTranslator
    ) {
    }

    public function execute(string $sourceText, string $sourceLanguage, string $targetLanguage): ?TranslationValueObject
    {
        return $this->externalTranslator->translate($sourceText, $sourceLanguage, $targetLanguage);
    }
}
