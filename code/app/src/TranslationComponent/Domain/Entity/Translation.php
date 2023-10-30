<?php

declare(strict_types=1);

namespace App\TranslationComponent\Domain\Entity;

use Symfony\Component\Uid\Uuid;

class Translation extends AbstractEntity
{
    public const STATUS_QUEUED = 1;
    public const STATUS_TRANSLATED = 2;

    private int $id;

    private string $sourceText;

    private string $sourceTextSlug;

    private string $sourceLanguage;

    private string $targetLanguage;

    private string $translatedText;

    private int $status = self::STATUS_QUEUED;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSourceText(): string
    {
        return $this->sourceText;
    }

    public function setSourceText(string $sourceText): void
    {
        $this->sourceText = $sourceText;
    }

    public function getSourceTextSlug(): string
    {
        return $this->sourceTextSlug;
    }

    public function setSourceTextSlug(string $sourceTextSlug): void
    {
        $this->sourceTextSlug = $sourceTextSlug;
    }

    public function getSourceLanguage(): string
    {
        return $this->sourceLanguage;
    }

    public function setSourceLanguage(string $sourceLanguage): void
    {
        $this->sourceLanguage = $sourceLanguage;
    }

    public function getTargetLanguage(): string
    {
        return $this->targetLanguage;
    }

    public function setTargetLanguage(string $targetLanguage): void
    {
        $this->targetLanguage = $targetLanguage;
    }

    public function getTranslatedText(): ?string
    {
        return $this->translatedText ?? null;
    }

    public function setTranslatedText(string $translatedText): void
    {
        $this->translatedText = $translatedText;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getStatusText(): string
    {
        switch($this->status) {
            case self::STATUS_QUEUED:
                return 'Queued';
            case self::STATUS_TRANSLATED:
                return 'Translated';
        }
    }
}
