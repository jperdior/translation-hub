<?php

declare(strict_types=1);

namespace App\Tests\Unit\UseCase;

use App\TranslationComponent\Domain\Repository\TranslationRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\TranslationComponent\Domain\UseCase\SearchStoredTranslationUseCase;
use App\TranslationComponent\Domain\Entity\Translation;

class SearchStoredTranslationUseCaseTest extends TestCase
{
    public function testSearchStoredTranslation()
    {

        $translationRepository = $this->createMock(TranslationRepositoryInterface::class);

        $useCase = new SearchStoredTranslationUseCase($translationRepository);

        $expectedTranslation = new Translation();
        $expectedTranslation->setSourceText(sourceText: 'I am your father');
        $expectedTranslation->setSourceLanguage(sourceLanguage: 'EN');
        $expectedTranslation->setTargetLanguage(targetLanguage: 'DE');
        $expectedTranslation->setStatus(status: Translation::STATUS_TRANSLATED);

        $translationRepository->expects($this->once())
            ->method('findTranslation')
            ->with(
                sourceText: 'I am your father',
                sourceLanguage: 'EN',
                targetLanguage: 'DE',
            )
        ->willReturn($expectedTranslation);

        $translation = $useCase->execute(
            sourceText: 'I am your father',
            sourceLanguage: 'EN',
            targetLanguage: 'DE'
        );

        $this->assertEquals(
            expected: $expectedTranslation,
            actual: $translation
        );



    }
}
