<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\DataTransformer;

use App\TranslationComponent\Domain\Entity\AbstractEntity;
use App\TranslationComponent\Presentation\Swagger\AbstractSwaggerClass;

interface ReadDataTransformerInterface
{
    public function toEntity(array $data): AbstractEntity;

    public function toSwaggerClass(AbstractEntity $entity, AbstractSwaggerClass $swagger): void;
}
