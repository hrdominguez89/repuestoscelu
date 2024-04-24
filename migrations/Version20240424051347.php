<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240424051347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand DROP image');
        $this->addSql('ALTER TABLE mia_brand DROP description_es');
        $this->addSql('ALTER TABLE mia_brand DROP description_en');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_brand ADD image TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD description_es TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD description_en TEXT DEFAULT NULL');
    }
}
