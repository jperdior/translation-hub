<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\DataTransformer;

use App\TranslationComponent\Domain\Entity\AbstractEntity;
use App\TranslationComponent\Presentation\Swagger\AbstractSwaggerClass;
use App\TranslationComponent\Presentation\Swagger\TranslationSwagger;
use App\TranslationComponent\Domain\Entity\Translation;

class TranslationDataTransformer implements ReadDataTransformerInterface
{
    public function toEntity(array $data): AbstractEntity
    {
        // TODO: Implement toEntity() method.
    }

    public function toSwaggerClass(?AbstractEntity $entity, AbstractSwaggerClass $swagger): void
    {
        /**
         * @var Translation $entity
         */
        $swagger->id = $entity->getId();
        $swagger->sourceText = $entity->getSourceText();
        $swagger->sourceLanguage = $entity->getSourceLanguage();
        $swagger->targetLanguage = $entity->getTargetLanguage();
        $swagger->translatedText = $entity->getTranslatedText() ?? '';
        $swagger->status = $entity->getStatusText();
    }
}
