<?php

declare(strict_types=1);

namespace App\TranslationComponent\Application\Command;

use App\TranslationComponent\Application\DataTransformer\TranslationDataTransformer;
use App\TranslationComponent\Application\Exception\QueueTranslationException;
use App\TranslationComponent\Domain\Repository\TransactionRepositoryInterface;
use App\TranslationComponent\Domain\UseCase\SaveQueuedTranslationUseCase;
use App\TranslationComponent\Presentation\Swagger\TranslationSwagger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;
use Exception;

#[AsMessageHandler]
class QueueTranslationMessageHandler
{
    public function __construct(
        private readonly SaveQueuedTranslationUseCase $saveQueuedTranslationUseCase,
        private readonly TransactionRepositoryInterface    $transactionRepository,
        private readonly TranslationDataTransformer     $translationDataTransformer,
        private readonly LoggerInterface                $logger
    ) {
    }

    public function __invoke(
        QueueTranslationMessage $queueTranslationMessage
    ): void {
        try {
            $this->transactionRepository->open();

            $this->logger->info(
                'Queue translation for sourceText: ' . $queueTranslationMessage->translationSwagger->sourceText . ' and sourceLanguage: ' .
                $queueTranslationMessage->translationSwagger->sourceLanguage . ' and targetLanguage: ' . $queueTranslationMessage->translationSwagger->targetLanguage
            );

            $translation = $this->saveQueuedTranslationUseCase->execute(
                sourceText: $queueTranslationMessage->translationSwagger->sourceText,
                sourceLanguage: $queueTranslationMessage->translationSwagger->sourceLanguage,
                targetLanguage: $queueTranslationMessage->translationSwagger->targetLanguage
            );

            $this->transactionRepository->commit();
        } catch (Exception $e) {
            $this->transactionRepository->rollback();
            $this->logger->error('QueueTranslationMessageHandler: ' . $e->getMessage());
            throw new QueueTranslationException($e->getMessage());
        }

        $this->logger->info('Queued translation: ' . $translation->getId());
        $this->translationDataTransformer->toSwaggerClass($translation, $queueTranslationMessage->translationSwagger);
    }
}
