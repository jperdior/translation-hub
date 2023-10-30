<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\ExternalService;

use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;

interface ExternalTranslatorInterface
{
    public function translate(string $sourceText, string $sourceLanguage, string $targetLanguage): ?TranslationValueObject;
}
