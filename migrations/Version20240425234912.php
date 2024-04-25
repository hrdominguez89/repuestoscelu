<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240425234912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37d966bf49');
        $this->addSql('DROP SEQUENCE models_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE model_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE model (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO model (id,name) SELECT id, name FROM models');
        $this->addSql('DROP TABLE models');
        $this->addSql('DROP INDEX idx_540dea37d966bf49');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN models_id TO model_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA377975B7E7 FOREIGN KEY (model_id) REFERENCES model (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA377975B7E7 ON mia_product (model_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA377975B7E7');
        $this->addSql('DROP SEQUENCE model_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE models_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE models (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP INDEX IDX_540DEA377975B7E7');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN model_id TO models_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37d966bf49 FOREIGN KEY (models_id) REFERENCES models (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea37d966bf49 ON mia_product (models_id)');
    }
}
