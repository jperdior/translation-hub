<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\ExternalService;

use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;

interface TranslationProviderInterface
{
    public function translate(string $sourceText, string $sourceLanguage, string $targetLanguage): ?TranslationValueObject;
}
