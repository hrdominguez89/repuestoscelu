<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405150301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37c5b9a28c');
        $this->addSql('DROP INDEX idx_540dea37c5b9a28c');
        $this->addSql('ALTER TABLE mia_product DROP status_sent_3pl_id');
        $this->addSql('ALTER TABLE mia_product DROP id3pl');
        $this->addSql('ALTER TABLE mia_product DROP attempts_send_3pl');
        $this->addSql('ALTER TABLE mia_product DROP error_message_3pl');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product ADD status_sent_3pl_id INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD id3pl INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD attempts_send_3pl SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD error_message_3pl TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37c5b9a28c FOREIGN KEY (status_sent_3pl_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea37c5b9a28c ON mia_product (status_sent_3pl_id)');
    }
}
