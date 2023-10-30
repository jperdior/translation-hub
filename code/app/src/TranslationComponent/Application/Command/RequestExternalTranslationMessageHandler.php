<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\Command;

use App\TranslationComponent\Application\Exception\RequestExternalTranslationException;
use App\TranslationComponent\Domain\Repository\TransactionRepositoryInterface;
use App\TranslationComponent\Domain\UseCase\RequestExternalTranslationUseCase;
use App\TranslationComponent\Domain\UseCase\SearchStoredTranslationUseCase;
use App\TranslationComponent\Domain\UseCase\UpdateQueuedTranslationUseCase;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;
use Exception;

#[AsMessageHandler]
class RequestExternalTranslationMessageHandler
{
    public function __construct(
        private readonly RequestExternalTranslationUseCase $requestExternalTranslationUseCase,
        private readonly SearchStoredTranslationUseCase    $searchStoredTranslationUseCase,
        private readonly UpdateQueuedTranslationUseCase    $updateQueuedTranslationUseCase,
        private readonly TransactionRepositoryInterface    $transactionRepository,
        private readonly LoggerInterface                   $logger
    ) {
    }

    public function __invoke(RequestExternalTranslationMessage $requestExternalTranslationMessage): void
    {
        try {
            $this->transactionRepository->open();

            $this->logger->info(
                'Request external translation for sourceText: ' . $requestExternalTranslationMessage->translationSwagger->sourceText . ' and sourceLanguage: ' .
                $requestExternalTranslationMessage->translationSwagger->sourceLanguage . ' and targetLanguage: ' . $requestExternalTranslationMessage->translationSwagger->targetLanguage
            );

            $translationValueObject = $this->requestExternalTranslationUseCase->execute(
                sourceText: $requestExternalTranslationMessage->translationSwagger->sourceText,
                sourceLanguage: $requestExternalTranslationMessage->translationSwagger->sourceLanguage,
                targetLanguage: $requestExternalTranslationMessage->translationSwagger->targetLanguage
            );

            $this->logger->info('Translated succesfully');

            $this->logger->info('Search stored queued translation');

            $translation = $this->searchStoredTranslationUseCase->execute(
                sourceText: $translationValueObject->sourceText,
                sourceLanguage: $translationValueObject->sourceLanguage,
                targetLanguage: $translationValueObject->targetLanguage
            );

            $this->logger->info('Update queued translation');

            $this->updateQueuedTranslationUseCase->execute(
                $translation,
                $translationValueObject->translatedText
            );

            $this->transactionRepository->commit();
        } catch (Exception $e) {
            $this->transactionRepository->rollback();
            $this->logger->error('RequestExternalTranslationMessageHandler: ' . $e->getMessage());
            throw new RequestExternalTranslationException($e->getMessage());
        }
    }
}
