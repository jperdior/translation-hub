<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\Command;

use App\TranslationComponent\Infrastructure\Messenger\CommandMessage;
use App\TranslationComponent\Presentation\Swagger\TranslationSwagger;

class QueueTranslationMessage implements CommandMessage
{
    public function __construct(
        public TranslationSwagger $translationSwagger
    ) {
    }
}
