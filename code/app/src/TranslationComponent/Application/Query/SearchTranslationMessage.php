<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\Query;

use App\TranslationComponent\Infrastructure\Messenger\QueryMessage;
use App\TranslationComponent\Presentation\Swagger\TranslationSwagger;

class SearchTranslationMessage implements QueryMessage
{
    public function __construct(
        public TranslationSwagger $translationSwagger
    ) {
    }
}
