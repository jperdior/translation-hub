<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\ValueObject;

class TranslationValueObject
{
    public function __construct(
        public readonly string $sourceText,
        public readonly string $sourceLanguage,
        public readonly string $targetLanguage,
        public readonly string $translatedText,
    ) {
    }
}
