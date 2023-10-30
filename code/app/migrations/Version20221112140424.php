<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221112140424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C6B7DA87E70A1DC ON translations');
        $this->addSql('DROP INDEX UNIQ_C6B7DA87678AA343 ON translations');
        $this->addSql('CREATE UNIQUE INDEX source ON translations (source_text, source_language)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX source ON translations');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6B7DA87E70A1DC ON translations (target_language)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6B7DA87678AA343 ON translations (translated_text)');
    }
}
