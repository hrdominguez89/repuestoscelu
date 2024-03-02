<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020031154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand ADD id3pl BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand DROP api_id');
        $this->addSql('ALTER TABLE mia_category ALTER nomenclature TYPE VARCHAR(2)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category ALTER nomenclature TYPE VARCHAR(3)');
        $this->addSql('ALTER TABLE mia_brand ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand DROP id3pl');
    }
}
