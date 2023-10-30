<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\TranslationComponent\Domain\ExternalService\ExternalTranslatorInterface;
use App\TranslationComponent\Domain\UseCase\RequestExternalTranslationUseCase;
use App\TranslationComponent\Domain\ValueObject\TranslationValueObject;

class RequestExternalTranslationUseCaseTest extends TestCase
{
    public function testExecute(): void
    {
        $externalTranslator = $this->createMock(ExternalTranslatorInterface::class);

        $useCase = new RequestExternalTranslationUseCase(
            externalTranslator: $externalTranslator
        );

        $translationValueObject = new TranslationValueObject(
            sourceText: 'I am your father',
            sourceLanguage: 'EN',
            targetLanguage: 'DE',
            translatedText: '',
        );

        $externalTranslator->expects($this->once())
            ->method('translate')
            ->with(
                sourceText: 'I am your father',
                sourceLanguage: 'EN',
                targetLanguage: 'DE',
            )
            ->willReturn($translationValueObject);

        $result = $useCase->execute(
            sourceText: 'I am your father',
            sourceLanguage: 'EN',
            targetLanguage: 'DE'
        );

        $this->assertEquals(
            expected: $translationValueObject,
            actual: $result
        );
    }
}
