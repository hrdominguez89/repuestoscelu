<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405085830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand DROP CONSTRAINT fk_8ee83afbc5b9a28c');
        $this->addSql('DROP INDEX idx_8ee83afbc5b9a28c');
        $this->addSql('ALTER TABLE mia_brand DROP status_sent_3pl_id');
        $this->addSql('ALTER TABLE mia_brand DROP id3pl');
        $this->addSql('ALTER TABLE mia_brand DROP attempts_send_3pl');
        $this->addSql('ALTER TABLE mia_brand DROP error_message_3pl');
        $this->addSql('ALTER TABLE mia_brand ALTER visible SET DEFAULT true');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_brand ADD status_sent_3pl_id INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD id3pl BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD attempts_send_3pl SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD error_message_3pl TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand ALTER visible SET DEFAULT false');
        $this->addSql('ALTER TABLE mia_brand ADD CONSTRAINT fk_8ee83afbc5b9a28c FOREIGN KEY (status_sent_3pl_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8ee83afbc5b9a28c ON mia_brand (status_sent_3pl_id)');
    }
}
