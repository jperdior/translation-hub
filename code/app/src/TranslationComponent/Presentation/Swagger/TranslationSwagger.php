<?php

declare(strict_types=1);

namespace App\TranslationComponent\Presentation\Swagger;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\Post;
use App\TranslationComponent\Presentation\Swagger\Validator\SourceLanguageValidator;
use App\TranslationComponent\Presentation\Swagger\Validator\TargetLanguageValidator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\TranslationComponent\Presentation\Controller\Translation\TranslateController;

#[ApiResource(
    operations: [
        new Get(
            controller: NotFoundAction::class,
            output: false,
            read: false
        ),
        new Post(
            uriTemplate: '/translate',
            status: 201,
            controller: TranslateController::class,
            openapiContext: [
                'summary' => 'Translates a text',
                'description' => 'Translates a texts',
            ],
            normalizationContext: [
                'groups' => ['read'],
            ],
            denormalizationContext: [
                'groups' => ['create'],
            ],
            read: false,
            serialize: true,
        )
    ],
)]
class TranslationSwagger extends AbstractSwaggerClass
{
    public const STATUS_QUEUED = 'Queued';
    public const STATUS_TRANSLATED = 'Translated';

    #[ApiProperty(
        description: 'Identifier',
        identifier: true,
        example: 1,
    )]
    public int $id;

    #[ApiProperty(
        description: 'Source text',
        example: 'Text to be translated',
    )]
    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\NotNull]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Text to translate cannot be longer than {{ limit }} characters',
    )]
    #[Groups(
        ['create','read']
    )]
    public string $sourceText;

    #[ApiProperty(
        description: 'Source language',
        example: 'EN',
    )]
    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Choice(
        choices: SourceLanguageValidator::AVAILABLE_LANGUAGES,
        message: 'The value {{ value }} is not a valid language.',
    )]
    #[Assert\NotNull]
    #[Groups(
        ['create','read']
    )]
    public string $sourceLanguage;

    #[ApiProperty(
        description: 'Target language',
        example: 'ES',
    )]
    #[Assert\Type(
        type: 'string',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\Choice(
        choices: TargetLanguageValidator::AVAILABLE_LANGUAGES,
        message: 'The value {{ value }} is not a valid language.',
    )]
    #[Assert\NotNull]
    #[Groups(
        ['create','read']
    )]
    public string $targetLanguage;

    #[ApiProperty(
        description: 'Translated text',
        example: 'Translated text',
    )]
    #[Groups(
        ['read']
    )]
    public string $translatedText = '';

    #[ApiProperty(
        description: 'Translation status',
        example: 'Translation status',
    )]
    #[Groups(
        ['read']
    )]
    public string $status;
}
