<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\Repository;

use App\TranslationComponent\Domain\Entity\Translation;

interface TranslationRepositoryInterface
{
    public function findTranslation(string $sourceText, string $sourceLanguage, string $targetLanguage): ?Translation;

    public function save(Translation $translation): void;
}
