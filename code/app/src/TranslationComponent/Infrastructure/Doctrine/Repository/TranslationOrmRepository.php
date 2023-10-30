<?php

declare(strict_types=1);

namespace App\TranslationComponent\Infrastructure\Doctrine\Repository;

use App\TranslationComponent\Domain\Entity\Translation;
use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;

class TranslationOrmRepository extends AbstractOrmRepository implements TranslationRepositoryInterface
{
    protected function getClass(): string
    {
        return Translation::class;
    }

    public function findTranslation(string $sourceText, string $sourceLanguage, string $targetLanguage): ?Translation
    {
        return $this->findOneBy([
            'sourceText' => $sourceText,
            'sourceLanguage' => $sourceLanguage,
            'targetLanguage' => $targetLanguage
        ]);
    }

    public function save(Translation $translation): void
    {
        $this->getEntityManager()->persist($translation);
        $this->getEntityManager()->flush();
    }
}
