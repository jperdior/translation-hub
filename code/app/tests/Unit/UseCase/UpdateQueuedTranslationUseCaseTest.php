<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase;

use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\TranslationComponent\Domain\UseCase\UpdateQueuedTranslationUseCase;
use App\TranslationComponent\Domain\Entity\Translation;

class UpdateQueuedTranslationUseCaseTest extends TestCase
{
    public function testSaveQueuedTranslation()
    {

        $translationRepository = $this->createMock(TranslationRepositoryInterface::class);
        $translationToUpdate = $this->createMock(Translation::class);

        $useCase = new UpdateQueuedTranslationUseCase($translationRepository);

        $translationToUpdate->setSourceText('I am your father');
        $translationToUpdate->setSourceLanguage('EN');
        $translationToUpdate->setTargetLanguage('DE');

        $translationToUpdate->expects($this->once())
            ->method('setStatus')
            ->with(
                status: Translation::STATUS_TRANSLATED
            );

        $translationToUpdate->expects($this->once())
            ->method('setTranslatedText')
            ->with(
                translatedText: 'Ich bin dein Vater'
            );

        $translationRepository->expects($this->once())
            ->method('save')
            ->with(
                translation: $translationToUpdate
            );

        $useCase->execute(translation: $translationToUpdate, translatedText: 'Ich bin dein Vater');

        $expectedTranslation = $this->createMock(Translation::class);
        $expectedTranslation->setSourceText(sourceText: 'I am your father');
        $expectedTranslation->setSourceLanguage(sourceLanguage: 'EN');
        $expectedTranslation->setTargetLanguage(targetLanguage: 'DE');
        $expectedTranslation->setStatus(status: Translation::STATUS_TRANSLATED);
        $expectedTranslation->setTranslatedText(translatedText: 'Ich bin dein Vater');

        $this->assertEquals(
            expected: $expectedTranslation,
            actual: $translationToUpdate
        );



    }
}
