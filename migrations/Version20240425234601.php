<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425234601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea375c002039');
        $this->addSql('DROP SEQUENCE colors_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE color_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE color (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE colors');
        $this->addSql('DROP INDEX idx_540dea375c002039');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN colors_id TO color_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA377ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA377ADA1FB5 ON mia_product (color_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA377ADA1FB5');
        $this->addSql('DROP SEQUENCE color_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE colors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE colors (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP INDEX IDX_540DEA377ADA1FB5');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN color_id TO colors_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea375c002039 FOREIGN KEY (colors_id) REFERENCES colors (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea375c002039 ON mia_product (colors_id)');
    }
}
