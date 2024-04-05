<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405161256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT fk_9164b3bdb4b9e7e');
        $this->addSql('DROP INDEX idx_9164b3bdb4b9e7e');
        $this->addSql('ALTER TABLE mia_customer DROP status_sent_crm_id');
        $this->addSql('ALTER TABLE mia_customer DROP attempts_send_crm');
        $this->addSql('ALTER TABLE mia_customer DROP error_message_crm');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer ADD status_sent_crm_id INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD attempts_send_crm INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD error_message_crm VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT fk_9164b3bdb4b9e7e FOREIGN KEY (status_sent_crm_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9164b3bdb4b9e7e ON mia_customer (status_sent_crm_id)');
    }
}
