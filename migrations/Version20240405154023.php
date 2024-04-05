<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405154023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea379eea759');
        $this->addSql('DROP INDEX idx_540dea379eea759');
        $this->addSql('ALTER TABLE mia_product DROP inventory_id');
        $this->addSql('ALTER TABLE mia_product ALTER weight DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product ADD inventory_id INT NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER weight SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea379eea759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea379eea759 ON mia_product (inventory_id)');
    }
}
