<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418221742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand DROP principal');
        $this->addSql('ALTER TABLE mia_category DROP principal');
        $this->addSql('ALTER TABLE mia_tag DROP principal');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category ADD principal BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD principal BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE mia_tag ADD principal BOOLEAN DEFAULT false NOT NULL');
    }
}
