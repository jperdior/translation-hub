<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase;

use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\TranslationComponent\Domain\UseCase\SaveQueuedTranslationUseCase;
use App\TranslationComponent\Domain\Entity\Translation;

class SaveQueuedTranslationUseCaseTest extends TestCase
{
    public function testSaveQueuedTranslation()
    {

        $translationRepository = $this->createMock(TranslationRepositoryInterface::class);

        $useCase = new SaveQueuedTranslationUseCase($translationRepository);

        $translationToSave = new Translation();
        $translationToSave->setSourceText('I am your father');
        $translationToSave->setSourceLanguage('EN');
        $translationToSave->setTargetLanguage('DE');

        $translationRepository->expects($this->once())
            ->method('save')
            ->with(
                translation: $translationToSave
            );

        $translation = $useCase->execute(
            sourceText: 'I am your father',
            sourceLanguage: 'EN',
            targetLanguage: 'DE'
        );

        $expectedTranslation = new Translation();
        $expectedTranslation->setSourceText(sourceText: 'I am your father');
        $expectedTranslation->setSourceLanguage(sourceLanguage: 'EN');
        $expectedTranslation->setTargetLanguage(targetLanguage: 'DE');
        $expectedTranslation->setStatus(status: Translation::STATUS_QUEUED);

        $this->assertEquals(
            expected: $expectedTranslation,
            actual: $translation
        );



    }
}
