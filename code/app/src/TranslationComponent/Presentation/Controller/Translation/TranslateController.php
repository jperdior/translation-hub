<?php

declare(strict_types=1);

namespace App\TranslationComponent\Presentation\Controller\Translation;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\TranslationComponent\Application\Command\QueueTranslationMessage;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\TranslationComponent\Application\Command\RequestExternalTranslationMessage;
use App\TranslationComponent\Infrastructure\Messenger\SimpleCommandBus;
use App\TranslationComponent\Infrastructure\Messenger\SimpleQueryBus;
use App\TranslationComponent\Presentation\Swagger\TranslationSwagger;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\TranslationComponent\Application\Query\SearchTranslationMessage;

#[AsController]
class TranslateController
{
    public function __invoke(
        ValidatorInterface $validator,
        SimpleQueryBus $queryBus,
        SimpleCommandBus $commandBus,
        TranslationSwagger $translationSwagger
    ): TranslationSwagger {
        $errors = $validator->validate($translationSwagger);

        if (count($errors) > 0) {
            throw new ValidationException(constraintViolationList: $errors);
        }

        $result = $queryBus->handle(query: new SearchTranslationMessage(translationSwagger: $translationSwagger));

        if ($result === null) {
            $commandBus->dispatch(new QueueTranslationMessage(translationSwagger: $translationSwagger));
        }

        if ($translationSwagger->status === TranslationSwagger::STATUS_QUEUED) {
            $commandBus->dispatch(new RequestExternalTranslationMessage(translationSwagger: $translationSwagger));
        }
        return $translationSwagger;
    }
}
